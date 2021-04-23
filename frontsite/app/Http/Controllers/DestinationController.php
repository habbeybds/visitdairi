<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Destination;
use App\Models\DestinationImage;
use App\Models\DestinationTag;
use App\Models\Review;
use App\Models\Product;
use DB;

class DestinationController extends BaseController
{

	protected $_config;
	protected $_func;

	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}

	public function detail($destinationId, $destinationSlug)
	{
		// get config
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$prefix = DB::getTablePrefix();
		
		$destination = Destination::select([
				'destinations.*',
			])
			->addSelect([
				'tags' => DestinationTag::select([
						DB::raw('GROUP_CONCAT(tag_id) AS tags')
					])
					->whereColumn('destination_tags.destination_id', 'destinations.destination_id'),
				'review_count' => Review::select([
						DB::raw('COUNT(review_id) as review_count')
					])
					->whereColumn('reviews.content_id', 'destinations.destination_id')
					->where('reviews.content_type', 'destination'),
				'review_avg' => Review::select([
					DB::raw('COALESCE(AVG(star_review),0)	 as review_avg')
				])
				->whereColumn('reviews.content_id', 'destinations.destination_id')
				->where('reviews.content_type', 'destination'),
			])
			->where('destinations.destination_id', $destinationId)
			->where('destinations.slug', $destinationSlug)
			->where('destinations.published', 1)
			->first();
			
		if($destination)
		{
			$id = $destination->destination_id;
			$images = DestinationImage::select([
				'image_url AS image'
			])
			->where('destination_id', $destination->destination_id)
			->get();

			$data['destination'] = $destination;
			$data['images'] = $images;

			// get related product
			$tags = explode(',', $destination->tags);
			$products = Product::inRandomOrder()
				->select([
					'products.product_id',
					'products.product_thumbnail',
					'products.title',
					'products.subtitle',
					'products.slug',
					'products.created_at',
					'product_types.slug AS product_type_slug',
					'product_types.name AS product_type',
					DB::raw('"IDR" AS currency'),
					DB::raw('-1 AS original_price'),
					'tours.description',
					DB::raw('(SELECT min(public_price) FROM '.DB::getTablePrefix().'tour_schedules WHERE tour_id='.DB::getTablePrefix().'tours.tour_id GROUP BY tour_id) as price'),
				//	'product_prices.public_price AS price',
				//	DB::raw('COUNT('.$prefix.'reviews.review_id) AS review_count'),
				//	DB::raw('AVG('.$prefix.'reviews.star_review) AS review_avg')
				])
				->join('product_types', 'product_types.product_type_id', 'products.product_type_id')
				->join('product_tags', 'product_tags.product_id','products.product_id')
				->join('tours', 'tours.product_id','products.product_id')
				->join('tags','tags.tag_id','product_tags.tag_id')
				->leftjoin('reviews', 'reviews.content_id','products.product_id')
				->where('reviews.content_type', 'product')
				->whereIn('tags.tag_id', $tags)
				->limit(4)
				->groupBy('products.product_id')
				->get();
			

			$data['products'] = [];
			if($products)
			{
				$dataProduct = [];
				foreach($products as $prod)
				{

					$dataProduct[] = [
						'product_id' => $prod->product_id,
						'slug' => $prod->slug,
						'product_type_slug' => $prod->product_type_slug,
						'product_thumbnail' => $prod->product_thumbnail,
						'product_type' => $prod->product_type,
						'title' => $prod->title,
						'subtitle' => $prod->subtitle,
						'description' => strip_tags($prod->description),
						'original_price' => -1,
						'price' => $prod->price,
						'currency' => 'IDR'
					];
				}
				$data['products'] = $dataProduct;
			}

			//return $data['products'];
			// get review
			$review = new Review;
			$data['reviews'] = $review->getReview('destination', $id);

			// counter destination viewed
			Destination::where('destination_id', $id)->update(['viewed' => DB::raw('viewed + 1')]);

			$data['related_product'] = $this->getRelatedProduct($tags);

			return view('destination.detail', $data);
		}
		return view('errors.404', $data);
	}

	public function ajaxGetDestination(Request $request)
	{
		$baseImage = config('constants.UPLOAD_PATH');
		$destination = Destination::inRandomOrder()
			->select([
				'destinations.*',
				DB::raw('GROUP_CONCAT('.DB::getTablePrefix().'reviews.star_review) AS review_count'),
				DB::raw('COALESCE(AVG('.DB::getTablePrefix().'reviews.star_review),0) AS review_avg')
			])
			->addSelect([
				'image' => 	DestinationImage::select([
					DB::raw('CONCAT("'.$baseImage.'",image_url) as image_url')
				])
				->whereColumn('destination_images.destination_id','destinations.destination_id')
				->limit(1)
			])
			->leftjoin('reviews', function($join) {
				$join->on('reviews.content_id','=','destinations.destination_id');
				$join->where('reviews.content_type','=','destination');
			})
			->where('destinations.published',1)
			->groupBy('destinations.destination_id')
			->paginate(9);
		return $destination;
	}
	
	private function getRelatedProduct($tags)
	{
		$related = array();

		$data_tour = Product::join('tours','tours.product_id','products.product_id')
							->join('tour_schedules','tour_schedules.tour_id','tours.tour_id')
							->join('product_tags','product_tags.product_id','products.product_id')
							->select([
								'products.title',
								'products.subtitle',
								'products.star_rating',
								DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","tour/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
								DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
								'tours.description',
								'tour_schedules.public_price'
							])
							->where('products.product_type_id',1)
							->where('products.status',1)
							->whereDate('tour_schedules.schedule_date','=',date("Y-m-d", strtotime("+3 days +7 hours")))
							->where('tour_schedules.outstanding_capacity','>=',1)
							->where('tour_schedules.public_price','>',0)
							->whereIn('product_tags.tag_id',$tags)
							// ->limit(4)
							->inRandomOrder()
							->first();
		if($data_tour)
		{
			$product_tour = '<div class="list col-12 col-md-6">' .
							'<a href="' . $data_tour->url . '">' .
								'<div class="wrap-img">' .
									'<div class="side-effect"></div>' .
									'<img src="' . $data_tour->product_thumbnail . '" alt="img"/>' .
								'</div>
								<div class="body-list">' .
									'<div class="content">' .
										'<div class="badge-product">' .
											'<h6>PAKET WISATA</h6>' .
											'<span><i class="fas fa-star"></i> ' . $data_tour->star_rating . '</span>' .
										'</div>' .
										'<h3>' . $data_tour->title . '</h3>' .
										'<p>' . strip_tags($data_tour->description) . '</p>' .
									'</div>' .
								'</div>' .
							'</a>' .
						'</div>';

			$related[] = [
				'product_related' => $product_tour
			];
		}
		
		$data_souvenir = Product::join('product_tags','product_tags.product_id','products.product_id')
								->join('souvenirs','souvenirs.product_id','products.product_id')
								->select([
									'products.title',
									DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","souvenir/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
									DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
									'products.star_rating',
									'souvenirs.souvenir_description',
									'souvenirs.default_publish_price'
								])
								->where('products.product_type_id',2)
								->where('products.status',1)
								->whereIn('product_tags.tag_id',$tags)
								->inRandomOrder()
								->first();
		
		if($data_souvenir)
		{
			$product_souvenir = '<div class="list col-12 col-md-6">' .
							'<a href="' . $data_souvenir->url . '">' .
								'<div class="wrap-img">' .
									'<div class="side-effect"></div>' .
									'<img src="' . $data_souvenir->product_thumbnail . '" alt="img"/>' .
								'</div>
								<div class="body-list">' .
									'<div class="content">' .
										'<div class="badge-product">' .
											'<h6>SOUVENIR</h6>' .
											'<span><i class="fas fa-star"></i> ' . $data_souvenir->star_rating . '</span>' .
										'</div>' .
										'<h3>' . $data_souvenir->title . '</h3>' .
										'<p>' . strip_tags($data_souvenir->souvenir_description) . '</p>' .
									'</div>' .
								'</div>' .
							'</a>' .
						'</div>';

			$related[] = [
				'product_related' => $product_souvenir
			];
		}

		$data_kuliner = Product::join('product_tags','product_tags.product_id','products.product_id')
								->join('culinaries','culinaries.product_id','products.product_id')
								->select([
									'products.title',
									DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","kuliner/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
									DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
									'products.star_rating',
									'culinaries.culinary_description'
								])
								->distinct()
								->where('products.product_type_id',3)
								->where('products.status',1)
								->whereIn('product_tags.tag_id',$tags)
								// ->limit(4)
								->inRandomOrder()
								->first();
		
		if($data_kuliner)
		{
			$product_kuliner = '<div class="list col-12 col-md-6">' .
							'<a href="' . $data_kuliner->url . '">' .
								'<div class="wrap-img">' .
									'<div class="side-effect"></div>' .
									'<img src="' . $data_kuliner->product_thumbnail . '" alt="img"/>' .
								'</div>
								<div class="body-list">' .
									'<div class="content">' .
										'<div class="badge-product">' .
											'<h6>KULINER</h6>' .
											'<span><i class="fas fa-star"></i> ' . $data_kuliner->star_rating . '</span>' .
										'</div>' .
										'<h3>' . $data_kuliner->title . '</h3>' .
										'<p>' . strip_tags($data_kuliner->culinary_description) . '</p>' .
									'</div>' .
								'</div>' .
							'</a>' .
						'</div>';

			$related[] = [
				'product_related' => $product_kuliner
			];
		}
		

		$data_akomodasi = Product::join('hotels','hotels.product_id','products.product_id')
						->join('hotel_schedules','hotel_schedules.hotel_id','hotels.hotel_id')
						->join('product_tags','product_tags.product_id','products.product_id')
						->join('partners','partners.partner_id','products.partner_id')
						->select([
							DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","kuliner/",'.DB::getTablePrefix().'partners.partner_id,"-",'.DB::getTablePrefix().'partners.slug) AS url'),
							DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'partners.company_thumbnail) AS product_thumbnail'),
							'partners.company_name',
							'partners.company_overview'
						])
						->distinct()
						->where('products.product_type_id',4)
						->where('products.status',1)
						->whereDate('hotel_schedules.schedule_date','>=',date("Y-m-d", strtotime("+3 days +7 hours")))
						->whereDate('hotel_schedules.schedule_date','<=',date("Y-m-d", strtotime("+3 days +7 hours")))
						->where('hotel_schedules.public_price','>',0)
						->whereIn('product_tags.tag_id',$tags)
						->inRandomOrder()
						->first();

		if($data_akomodasi)
		{
			$product_akomodasi = '<div class="list col-12 col-md-6">' .
								'<a href="' . $data_akomodasi->url . '">' .
									'<div class="wrap-img">' .
										'<div class="side-effect"></div>' .
										'<img src="' . $data_akomodasi->product_thumbnail . '" alt="img"/>' .
									'</div>
									<div class="body-list">' .
										'<div class="content">' .
											'<div class="badge-product">' .
												'<h6>AKOMODASI</h6>' .
											'</div>' .
											'<h3>' . $data_akomodasi->company_name . '</h3>' .
											'<p>' . strip_tags($data_akomodasi->company_overview) . '</p>' .
										'</div>' .
									'</div>' .
								'</a>' .
							'</div>';

			$related[] = [
				'product_related' => $product_akomodasi
			];
		}

		$data_transportasi = Product::join('rent_cars','rent_cars.product_id','products.product_id')
									->join('rent_car_schedules','rent_car_schedules.rent_car_id','rent_cars.rent_car_id')
									->join('partners','partners.partner_id','products.partner_id')
									->join('car_brands','rent_cars.car_brand_id','car_brands.car_brand_id')
									->join('car_models','rent_cars.car_model_id','car_models.car_model_id')
									->select([
										'products.star_rating',
										DB::raw('CONCAT('.DB::getTablePrefix().'car_brands.name," ", '.DB::getTablePrefix().'car_models.name) AS product_name'),
										DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","transportasi/detail/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
										DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'car_models.car_image) AS product_thumbnail'),
										'rent_cars.is_driver',
										'rent_cars.is_gas',
										'rent_cars.is_water',
										'rent_cars.is_insurance',
										'rent_cars.is_reschedule',
										'rent_cars.is_refund'
									])
									->distinct()
									->where('products.product_type_id',5)
									->where('products.status',1)
									->whereDate('rent_car_schedules.schedule_date','>=',date("Y-m-d", strtotime("+3 days +7 hours")))
									->whereDate('rent_car_schedules.schedule_date','<=',date("Y-m-d", strtotime("+3 days +7 hours")))
									->where('rent_car_schedules.outstanding_capacity','>=',1)
									->where('rent_car_schedules.public_price','>',0)
									->inRandomOrder()
									->first();
		
		if($data_transportasi)
		{
			$product_transportasi = '<div class="list col-12 col-md-6">' .
								'<a href="' . $data_transportasi->url . '?start=' . date("d-m-Y", strtotime("+3 days +7 hours")) . '&end=' . date("d-m-Y", strtotime("+3 days +7 hours")) . '&qty=1&driver=0' . '">' .
									'<div class="wrap-img">' .
										'<div class="side-effect"></div>' .
										'<img src="' . $data_transportasi->product_thumbnail . '" alt="img"/>' .
									'</div>
									<div class="body-list">' .
										'<div class="content">' .
											'<div class="badge-product">' .
												'<h6>TRANSPORTASI</h6>' .
												'<span><i class="fas fa-star"></i> ' . $data_transportasi->star_rating . '</span>' .
											'</div>' .
											'<h3>' . $data_transportasi->product_name . '</h3>' .
											'<p style="display: block;">';
											
											if($data_transportasi->is_driver){
												if($data_transportasi->is_gas){
													$product_transportasi .= "<i class='fas fa-male'></i> Supir & Bensin<br>";
												} else {
													$product_transportasi .= "<i class='fas fa-male'></i> Supir<br>";
												}                                
											}
											
											if($data_transportasi->is_water){
												$product_transportasi .= "<i class='fab fa-gulp'></i> Air Mineral<br>";
											}
											
											if($data_transportasi->is_insurance){
												$product_transportasi .= "<i class='fas fa-car'></i> Asuransi<br>";
											}
											
											if($data_transportasi->is_reschedule){
												$product_transportasi .= "<i class='fas fa-hourglass-half'></i> Dapat Dijadwalkan Ulang<br>";
											}
											
											if($data_transportasi->is_refund){
												$product_transportasi .= "<i class='fas fa-sticky-note'></i> Dapat Direfund<br>";
											}
											
			$product_transportasi .= '</p>' .
											'</div>' .
										'</div>' .
									'</a>' .
								'</div>';

			$related[] = [
				'product_related' => $product_transportasi
			];
		}

		return $related;
	}
}
