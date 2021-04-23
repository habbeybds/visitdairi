<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CartRepository;
use App\Repositories\RajaOngkirRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Product;
use App\Models\Souvenir;
use App\Models\SouvenirImage;
use App\Models\City;
use App\Models\SubDistrict;
use App\Models\Review;
use App\Models\Courier;
use DB;

class ProductSouvenirController extends BaseController
{

	protected $_config = null;
	protected $_func = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs; 
		$this->_func = $functions; 
	}

	public function detail($productId, $productSlug, RajaOngkirRepository $ongkir, CartRepository $cart)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$data['productType'] = 'souvenir';
		$data['cartKey'] = $cart->sessionCart(true);

		$product = Product::join('souvenirs', 'souvenirs.product_id', 'products.product_id')
			->join('partners', 'partners.partner_id', 'products.partner_id')
			->select([
				'products.*',
				'souvenirs.*',
				'partners.company_logo',
				'partners.company_name',
				'partners.url_map'
			])
			->addSelect([
				'avg_review' => Review::select([DB::raw('COALESCE(AVG(star_review), 0)')])
					->whereColumn('reviews.content_id', 'products.product_id')
					->limit(1),
				'total_review' => Review::select([DB::raw('COUNT(star_review)')])
					->whereColumn('reviews.content_id', 'products.product_id')
					->limit(1)
			])
			->where('products.product_id', $productId)
			->where('products.slug', $productSlug)
			->first();

		if($product)
		{
			$data['product'] = $product;
			//return $product;
			// get images
			$imageList = [];
			$images = SouvenirImage::where('souvenir_id', $product->souvenir_id)->get();
			if($images)
			{
				$mediaPath = config('constants.UPLOAD_PATH');
				$first = true;
				foreach($images as $img)
				{
					$mainclass = $first ? '-main h-2 w-2' : '';
					$imageList[] = [
						'id' => $img->souvenir_image_id,
						'title' => $img->title,
						'url' => $mediaPath . $img->image_url,
						'mainclass' => $mainclass
					];
					$first = false;
				}
			}
			$data['images'] = $imageList;

			$tags = array();
			foreach($product->producttag as $row){
				$tags[] = $row->tag_id;
			}

			$data['related_product'] = $this->getRelatedProduct($productId,$tags);

			return view('product.detail-souvenir', $data);
		}

		return view('errors.404', $data);
	}

	public function shipment($cartKey, Request $request, CartRepository $cart)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$auth = new CustomerRepository;

		$carts = $cart->getData($cartKey);
		if(!$carts)
		{
			return back();
		}
		$data['cartKey'] = $cartKey;
		$data['cart'] = $carts;

		$cartData = unserialize($carts->cart_param);
		$data['cartData'] = $cartData;
		$data['auth'] = $auth;
		
		$origin = $cartData['partner_city_id'];
		$originType = 'city';
		if(!empty($cartData['partner_subdistrict_id']))
		{
			$origin = $cartData['partner_subdistrict_id'];
			$originType = 'subdistrict';
		}
		$data['origin'] = $origin;
		$data['originType'] = $originType;

		$couriers = Courier::where('disabled', 0)->get();
		$data['couriers'] = $couriers;
		
		return view('order.form-shipment', $data);
	}

	public function getCity(RajaOngkirRepository $ongkir)
	{
		$city1 = 501;
		$city2 = 510;
		$inArray = [];
		for($i=$city1;$i<=$city2;$i++)
		{
			$inArray[] = $i;
		}
		$cities = City::whereIn('city_id', $inArray)->get();
		foreach($cities as $city)
		{
			$param = ['city'=>$city->city_id];
			$response = $ongkir->getSubdistrict($param);
			if(isset($response['rajaongkir']))
			{
				$response = $response['rajaongkir'];
				if($response['status']['code'] == 200) 
				{
					$results = $response['results'];
					$data = [];
					foreach($results as $res)
					{
						$data[] = [
							'subdistrict_id' => $res['subdistrict_id'],
							'city_id' => $res['city_id'],
							'subdistrict_name' => $res['subdistrict_name'],
						];
					}
					SubDistrict::insert($data);
				}
			}
		}
		
		return $response;
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
								DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","tour/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
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
									DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","souvenir/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
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
									DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","kuliner/",'.DB::getTablePrefix().'products.product_id,"-",'.DB::getTablePrefix().'products.slug) AS url'),
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
							DB::raw('CONCAT("'.config('constants.FRONTSITE_URL').'","kuliner/",'.DB::getTablePrefix().'partners.partner_id,"-",'.DB::getTablePrefix().'partners.slug) AS url'),
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