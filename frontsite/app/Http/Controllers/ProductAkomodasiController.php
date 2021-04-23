<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CartRepository;
use App\Models\Partner;
use App\Models\PartnerImage;
use App\Models\Hotel;
use App\Models\HotelSchedule;
use App\Models\HotelImage;
use App\Models\Product;
use App\Models\PostingCategory;
use App\Helpers\FunctionsHelper;
use DB;

class ProductAkomodasiController extends BaseController
{

	const PRODUCT_HOTEL = 4;

	protected $_config = null;
	protected $_func = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs; 
		$this->_func = $functions; 
	}

	public function detail($partnerId, $partnerSlug, CartRepository $cart)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$data['productType'] = 'akomodasi';
		$data['cartKey'] = $cart->sessionCart(true);

		$partner = Partner::where('partner_id', $partnerId)->first();
		if($partner)
		{
			$data['partner'] = $partner;
			$partnerImages = PartnerImage::where('partner_id',$partner->partner_id)->get();
			$imageList = [];
			if($partnerImages)
			{
				$mediaPath = config('constants.UPLOAD_PATH');
				$first = true;
				foreach($partnerImages as $img)
				{
					$mainclass = $first ? '-main h-2 w-2' : '';
					$imageList[] = [
						'id' => $img->tour_image_id,
						'title' => $img->title,
						'url' => $mediaPath . $img->image_url,
						'mainclass' => $mainclass
					];
					$first = false;
				}
			}
			$data['images'] = $imageList;
			$data['facilities'] = '';

			$products = Product::join('hotels', 'hotels.product_id', 'products.product_id')
				->select([
					'products.*',
					'hotels.*'
				])
				->addSelect([
					'low_price' => HotelSchedule::select([
						DB::raw('MIN(public_price)')
					])
					->whereColumn('hotel_schedules.hotel_id', 'hotels.hotel_id')
					->limit(1),
					'pricing' => HotelSchedule::select([
						DB::raw('GROUP_CONCAT(CONCAT(hotel_schedule_id,"|",public_price))')
					])
					->whereColumn('hotel_schedules.hotel_id', 'hotels.hotel_id')
					->limit(1),
				])
				->where('products.product_type_id', 4)
				->where('partner_id', $partner->partner_id)
				->get();
			$dataProduct = [];
			$tags = [];
			if(sizeof($products) > 0)
			{
				$data['facilities'] = $products[0]['room_facility'];
				foreach($products as $prod) 
				{
					$item = $prod->toArray();
					$pricelist = explode(',', $item['pricing']);
					foreach($pricelist as $list)
					{
						list($id, $price) = explode('|',$list);
						$item['pricelist'][] = [
							'schedule_id' => (int)$id,
							'price' => (float)$price
						];
						
					}
					$dataProduct[] = $item;
					foreach($prod->producttag as $row){
						$tags[] = $row->tag_id;
					}
				}
			}
			//return $dataProduct;
			$data['products'] = $dataProduct;
			$data['related_product'] = $this->getRelatedProduct($partnerId,$tags);

			return view('product.detail-akomodasi', $data);
		}

		return view('errors.404', $data);
	}

	public function getHotelAvail($partnerId, Request $request)
	{
		$date1 = $request->date1;
		$date2 = date('Y-m-d',strtotime('-1 day', strtotime($request->date2)));
		$numroom = $request->numroom;
		$numpax = $request->numpax;

		$nights = $this->_func->nightOfDays($date1, $date2);
		$data['days'] = $nights;
		$products = Product::join('hotels', 'hotels.product_id', 'products.product_id')
			->join('room_types', 'room_types.room_type_id', 'hotels.room_type_id')
			->select([
				'products.product_id',
				'products.partner_id',
				'products.title',
				'products.subtitle',
				'products.slug',
				'products.product_thumbnail',
				'hotels.*',
				'room_types.type_name AS room_type_name',
			])
			->addSelect([
				'allotment' => HotelSchedule::select([
						DB::raw('IF(MIN(outstanding_capacity) < '.(int)$numroom.',MIN(outstanding_capacity), '.(int)$numroom.')')
					])
					->whereBetween('schedule_date', [$date1, $date2])
					->whereColumn('hotel_schedules.hotel_id','hotels.hotel_id'),
				'hasSchedule' => HotelSchedule::select([
						DB::raw('count(hotel_schedule_id)')
					])
					->whereBetween('schedule_date', [$date1, $date2])
					->whereColumn('hotel_schedules.hotel_id','hotels.hotel_id'),
				'avg_price' => HotelSchedule::select([
						DB::raw('AVG(public_price)')
					])
					->whereBetween('schedule_date', [$date1, $date2])
					->whereColumn('hotel_schedules.hotel_id','hotels.hotel_id'),
				'total_price' => HotelSchedule::select([
						DB::raw('SUM(public_price)')
					])
					->whereBetween('schedule_date', [$date1, $date2])
					->whereColumn('hotel_schedules.hotel_id','hotels.hotel_id')
			])
			->having('hasSchedule', ($nights))
			->where('partner_id', $partnerId)
			->where('products.product_type_id', static::PRODUCT_HOTEL)
			->where('products.status', 1)
			->get();
		
		$data['products'] = $products;

		return view('product.list-room', $data);
	}

	public function getRoomDetail($partnerId, Request $request)
	{
		$date1 = $request->date1;
		$date2 = date('Y-m-d',strtotime('-1 day', strtotime($request->date2)));
		$data = [];
		$product = Product::join('hotels', 'hotels.product_id', 'products.product_id')
			->join('room_types', 'room_types.room_type_id', 'hotels.room_type_id')
			->select([
				'products.product_id',
				'products.partner_id',
				'products.title',
				'products.subtitle',
				'products.slug',
				'products.product_thumbnail',
				'hotels.*',
				'room_types.type_name AS room_type_name',
			])
			->addSelect([
				'avg_price' => HotelSchedule::select([
					DB::raw('AVG(public_price)')
				])
				->whereBetween('schedule_date', [$date1, $date2])
				->whereColumn('hotel_schedules.hotel_id','hotels.hotel_id'),
			])
			->where('products.product_id', $request->product_id)
			->first();

		if($product)
		{
			$images = HotelImage::select([
				'image_url'
			])
				->where('hotel_id',$product->hotel_id)
				->get();
			$data['images'] = $images;
		}

		$data['product'] = $product;

		return view('product.detail-room', $data);
	}
	
	private function getRelatedProduct($productid,$tags)
	{
		$related = array();

		$data_tour = Product::join('tours','tours.product_id','products.product_id')
							->join('tour_schedules','tour_schedules.tour_id','tours.tour_id')
							->join('product_tags','product_tags.product_id','products.product_id')
							->select([
								'products.title',
								'products.subtitle',
								'products.star_rating',
								DB::raw('CONCAT("' . url('/tour') . '/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
								DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
								'tours.description',
								'tour_schedules.public_price'
							])
							->where('products.product_type_id',1)
							->where('products.status',1)
							->where('products.product_id','!=',$productid)
							->whereDate('tour_schedules.schedule_date','=',date("Y-m-d", strtotime("+3 days")))
							->where('tour_schedules.outstanding_capacity','>=',1)
							->where('tour_schedules.public_price','>',0)
							->whereIn('product_tags.tag_id',$tags)
							// ->limit(4)
							->inRandomOrder()
							->first();

		if($data_tour){
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
									DB::raw('CONCAT("' . url('/souvenir') . '/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
									DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
									'products.star_rating',
									'souvenirs.souvenir_description',
									'souvenirs.default_publish_price'
								])
								->where('products.product_type_id',2)
								->where('products.status',1)
								->where('products.product_id','!=',$productid)
								->whereIn('product_tags.tag_id',$tags)
								->limit(2)
								->inRandomOrder()
								->get();
					
		if($data_souvenir){
			$product_souvenir = '';
			foreach($data_souvenir as $row_souvenir){
				$product_souvenir .= '<div class="list col-12 col-md-6">' .
										'<a href="' . $row_souvenir->url . '">' .
											'<div class="wrap-img">' .
												'<div class="side-effect"></div>' .
												'<img src="' . $row_souvenir->product_thumbnail . '" alt="img"/>' .
											'</div>
											<div class="body-list">' .
												'<div class="content">' .
													'<div class="badge-product">' .
														'<h6>SOUVENIR</h6>' .
														'<span><i class="fas fa-star"></i> ' . $row_souvenir->star_rating . '</span>' .
													'</div>' .
													'<h3>' . $row_souvenir->title . '</h3>' .
													'<p>' . strip_tags($row_souvenir->souvenir_description) . '</p>' .
												'</div>' .
											'</div>' .
										'</a>' .
									'</div>';
			}
			
			$related[] = [
				'product_related' => $product_souvenir
			];
		}
		
		$data_kuliner = Product::join('product_tags','product_tags.product_id','products.product_id')
								->join('culinaries','culinaries.product_id','products.product_id')
								->select([
									'products.title',
									DB::raw('CONCAT("' . url('/kuliner') . '/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
									DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
									'products.star_rating',
									'culinaries.culinary_description'
								])
								->distinct()
								->where('products.product_type_id',3)
								->where('products.status',1)
								->where('products.product_id','!=',$productid)
								->whereIn('product_tags.tag_id',$tags)
								// ->limit(4)
								->inRandomOrder()
								->first();
					
		if($data_kuliner){
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
							DB::raw('CONCAT("' . url('/akomodasi') . '/",'.DB::getTablePrefix().'partners.partner_id,"-",'.DB::getTablePrefix().'partners.slug) AS url'),
							DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'partners.company_thumbnail) AS product_thumbnail'),
							'partners.company_name',
							'partners.company_overview'
						])
						->distinct()
						->where('products.product_type_id',4)
						->where('products.status',1)
						->where('products.product_id','!=',$productid)
						->whereDate('hotel_schedules.schedule_date','>=',date("Y-m-d", strtotime("+3 days")))
						->whereDate('hotel_schedules.schedule_date','<=',date("Y-m-d", strtotime("+3 days")))
						->where('hotel_schedules.public_price','>',0)
						->whereIn('product_tags.tag_id',$tags)
						->inRandomOrder()
						->first();

		if($data_akomodasi){
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
										DB::raw('CONCAT("'.url('/transportasi/detail').'/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
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
									->where('products.product_id','!=',$productid)
									->whereDate('rent_car_schedules.schedule_date','>=',date("Y-m-d", strtotime("+3 days")))
									->whereDate('rent_car_schedules.schedule_date','<=',date("Y-m-d", strtotime("+3 days")))
									->where('rent_car_schedules.outstanding_capacity','>=',1)
									->where('rent_car_schedules.public_price','>',0)
									->inRandomOrder()
									->first();
					
		if($data_transportasi){
			$product_transportasi = '<div class="list col-12 col-md-6">' .
						'<a href="' . $data_transportasi->url . '?start=' . date("d-m-Y", strtotime("+3 days")) . '&end=' . date("d-m-Y", strtotime("+3 days")) . '&qty=1&driver=0' . '">' .
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