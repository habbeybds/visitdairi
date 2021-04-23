<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\CartRepository;
use App\Models\Partner;
use App\Models\Product;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\RentCar;
use App\Models\RentCarSchedule;
use App\Helpers\FunctionsHelper;
use DB;

use DateTime;

class ProductTransportasiController extends BaseController
{

	protected $_config = null;
	protected $_func = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs; 
		$this->_func = $functions; 
	}

	public function detail($productId, $productSlug)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$data['productType'] = 'transportasi';

		$carmodel = CarModel::find($productId);
		
		if($carmodel)
		{
			$data['product_id'] = $productId;
			$data['title'] = $carmodel->carbrand->name . ' ' . $carmodel->name;
			$data['thumbnail'] = config('constants.UPLOAD_PATH') . $carmodel->car_image;
			$data['passenger_capacity'] = $carmodel->passenger_capacity;

			return view('product.detail-transportasi', $data);
		}

		return view('errors.404', $data);
	}

	public function search(Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'product_id'=> 'required',
			'date_start'=> 'required',
			'date_end'=> 'required',
			'quantity'=> 'required'
		],[
			'product_id.required' => 'product_id wajib diisi!',
			'date_start.required'=> 'Tanggal Awal Penyewaan wajib diisi!',
			'date_end.required'=> 'Tanggal Akhir Penyewaan wajib diisi!',
			'quantity.required'=> 'Jumlah Kendaraan wajib diisi!'
		]);

		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		$productId = $request->product_id;
		$dateStart = $request->date_start;
		$dateEnd = $request->date_end;
		$quantity = $request->quantity;
		$driver = $request->driver;

		$startDate = new DateTime($dateStart);
		$endDate = new DateTime($dateEnd);
		if($startDate != $endDate){
			$interval = $startDate->diff($endDate);
			$totDays = $interval->format('%a') + 1;
		} else {
			$totDays = 1;
		}
		
		$rentcar = RentCar::whereHas('product', function($query){
								$query->where('status',1);
							})
							->whereHas('rentcarschedule', function($query) use ($startDate,$endDate,$quantity){
								$query->whereDate('schedule_date','>=',$startDate)->whereDate('schedule_date','<=',$endDate)->where('outstanding_capacity','>=',$quantity)->where('public_price','>',0);
							})
							->where('car_model_id',$productId)
							->where('is_driver',$driver)
							->get();

		$products = array();
		foreach($rentcar as $row){
			
			$totPublicPrice = RentCarSchedule::where('rent_car_id',$row->rent_car_id)
												->whereDate('schedule_date','>=',$startDate)
												->whereDate('schedule_date','<=',$endDate)
												->where('public_price','>',0)
												->sum('public_price');

			$nestedData = array();
			$nestedData['company_name'] =  $row->product->partner->company_name;
			$nestedData['company_thumbnail'] = config('constants.UPLOAD_PATH') . $row->product->partner->company_thumbnail;
			$nestedData['product_id'] = $row->product->product_id;
			$nestedData['price'] = number_format(($totPublicPrice/$totDays),0,",",".");
			$nestedData['url'] = $row->product->product_id . '-' . $row->product->slug . '?start=' . $request->date_start . '&end=' . $request->date_end . '&qty=' . $quantity . '&driver=' . $driver;
			$nestedData['star_rating'] = $row->product->star_rating;
			$nestedData['is_driver'] = $row->is_driver;
			$nestedData['is_gas'] = $row->is_gas;
			$nestedData['is_water'] = $row->is_water;
			$nestedData['is_insurance'] = $row->is_insurance;
			$nestedData['is_reschedule'] = $row->is_reschedule;
			$nestedData['is_refund'] = $row->is_refund;
			$products[] = $nestedData;
		}

		return $this->_func->resJSON(200,1,[
			'products' => $products,
			'rentcar' => $rentcar
		], '');
	}
	
	public function detailcar($productId, $productSlug, Request $request, CartRepository $cart)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$data['productType'] = 'transportasi';
		$data['cartKey'] = $cart->sessionCart(true);

		$startDate = $request->query('start');
		$endDate = $request->query('end');
		$qty = $request->query('qty');
		$driver = $request->query('driver');

		if(empty($endDate)){
			$totDays = 1;
		} else {
			$datetime1 = new DateTime($startDate);
			$datetime2 = new DateTime($endDate);
			$interval = $datetime1->diff($datetime2);
			$totDays = $interval->format('%a') + 1;
		}

		$products = Product::find($productId);

		if($products)
		{
			$partner = Partner::find($products->partner_id);
			$rentcar = RentCar::where('product_id', $products->product_id)->first();

			$totPublicPrice = RentCarSchedule::where('rent_car_id',$rentcar->rent_car_id)
												->whereDate('schedule_date','>=',new DateTime($startDate))
												->whereDate('schedule_date','<=',new DateTime($endDate))
												// ->where('public_price','>',0)
												->sum('public_price');

			$data['product'] = $products;
			$data['rentCarId'] = $rentcar->rent_car_id;
			$data['companyLogo'] = config('constants.UPLOAD_PATH') . $partner->company_logo;
			$data['companyName'] = $partner->company_name;
			$data['companyOverview'] = $partner->company_overview;
			$data['address'] = $partner->address;
			$data['rentTimeDesc'] = $rentcar->rent_time_desc;
			$data['tncDesc'] = $rentcar->tnc_desc;
			$data['priceInclude'] = $rentcar->price_include;
			$data['priceExclude'] = $rentcar->price_exclude;
			$data['thumbnail'] = config('constants.UPLOAD_PATH') . $rentcar->carmodel->car_image;
			$data['productTitle'] = $rentcar->carbrand->name . ' ' . $rentcar->carmodel->name;
			$data['passengerCapacity'] = $rentcar->carmodel->passenger_capacity;
			$data['startDate'] = $startDate;
			$data['endDate'] = (empty($endDate)) ? $startDate : $endDate;
			$data['strStartDate'] = $this->_func->scheduleStringDate($startDate);
			$data['strEndDate'] = (empty($endDate)) ? $this->_func->scheduleStringDate($startDate) : $this->_func->scheduleStringDate($endDate);
			$data['price'] = $totPublicPrice;
			$data['totDays'] = $totDays;
			$data['totCars'] = $qty;
			$data['totPrice'] = $totPublicPrice * intval($qty);
			$data['driver'] = $driver;
			$data['strDriver'] = ($driver == 1) ? 'YA' : 'TIDAK';

			$tags = array();
			foreach($products->producttag as $row){
				$tags[] = $row->tag_id;
			}

			$data['related_product'] = $this->getRelatedProduct($productId,$tags);

			return view('product.detail-transportasi-car', $data);
		}

		return view('errors.404', $data);
	}

	public function pickup($cartKey, Request $request, CartRepository $cart)
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
		
		
		
		return view('order.form-pickup', $data);
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