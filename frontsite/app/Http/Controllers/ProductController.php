<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FunctionsHelper;
use App\Repositories\CustomerRepository;
use App\Repositories\ConfigRepository;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductDetailImage;
use App\Models\ProductSchedule;
use App\Models\ProductItinerary;
use App\Models\ProductType;
use App\Models\HotelSchedule;
use App\Models\RentCarSchedule;
use App\Models\Tag;
use DB;
use DateTime;

class ProductController extends BaseController
{

	protected $_config;
	protected $_func;

	protected function scheduleStringDate($stringDate)
	{
		$DaysID = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
		$MonthID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

		$date = strtotime($stringDate);
		$stringDate = [
			$DaysID[date('w',$date)].',',
			date('d',$date),
			$MonthID[date('n',$date)-1],
			date('Y',$date)
		];
		return implode(' ', $stringDate);
	}

	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}

	public function products($productType)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$types = ProductType::get();
		$products = [];
		foreach($types as $type)
		{
			$products[$type->slug] = [
				'name' => $type->name,
				'data' => ''
			];
		}
		// $productTour = Product::join('tours', 'tours.product_id', 'products.product_id')
		// 	->select([
		// 		'products.product_id',
		// 		'products.title',
		// 		'products.subtitle',
		// 		'products.slug',
		// 		DB::raw('"tour" As product_type'),
		// 		DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
		// 		'tours.description',
		// 		'tours.availability_type',
		// 		DB::raw('COALESCE((SELECT MIN(public_price) FROM '.DB::getTablePrefix().'tour_schedules where tour_id= '.DB::getTablePrefix().'tours.tour_id AND schedule_date > curdate()),0) AS product_price')
		// 	])
		// 	->having('product_price','>', 0)
		// 	->paginate(4);

		// $data['tours'] = $productTour;

		$tourTag = Tag::join('product_tags','product_tags.tag_id','tags.tag_id')
			->join('products','products.product_id','product_tags.product_id')
			->select([
				'tags.tag_id',
				'tags.name',
			])
			->where('products.product_type_id',1)
			->distinct()
			->get();
			
		$souvenirTag = Tag::join('product_tags','product_tags.tag_id','tags.tag_id')
			->join('products','products.product_id','product_tags.product_id')
			->select([
				'tags.tag_id',
				'tags.name',
			])
			->where('products.product_type_id',2)
			->distinct()
			->get();
			
		$kulinerTag = Tag::join('product_tags','product_tags.tag_id','tags.tag_id')
			->join('products','products.product_id','product_tags.product_id')
			->select([
				'tags.tag_id',
				'tags.name',
			])
			->where('products.product_type_id',3)
			->distinct()
			->get();
			
		$akomodasiTag = Tag::join('product_tags','product_tags.tag_id','tags.tag_id')
			->join('products','products.product_id','product_tags.product_id')
			->select([
				'tags.tag_id',
				'tags.name',
			])
			->where('products.product_type_id',4)
			->distinct()
			->get();
		
		// $transportasiTag = Tag::join('product_tags','product_tags.tag_id','tags.tag_id')
		// 	->join('products','products.product_id','product_tags.product_id')
		// 	->select([
		// 		'tags.tag_id',
		// 		'tags.name',
		// 	])
		// 	->where('products.product_type_id',5)
		// 	->distinct()
		// 	->get();
			
		$data['tourTag'] = $tourTag;
		$data['souvenirTag'] = $souvenirTag;
		$data['kulinerTag'] = $kulinerTag;
		$data['akomodasiTag'] = $akomodasiTag;
		$data['activeTab'] = $productType;

		return view('product.all', $data);
	}

	public function detail($productType, $productId, $productSlug, Product $product)
	{

		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		
		$products = $product->getProduct($productId, $productSlug);

		if($products && $products->product_template == 'tour')
		{
			$data['images'] = [];
			$ProductImage = ProductImage::select([
					'product_image_id AS id',
					'title',
					'image_url AS image'
				])
				->where('product_id', $products->product_id)
				->get();
			if($ProductImage)
			{
				$data['images'] = $ProductImage;
			}

			$data['detail_images'] = [];
			$ProductDetailImage = ProductDetailImage::select([
					'product_detail_image_id AS id',
				 	'product_detail_id',
					'title',
				 	'image_url AS image',
				])
				->where('product_detail_id', $products->product_detail_id)
				->get();
			if($ProductDetailImage)
			{
				$data['detail_images'] = $ProductDetailImage;
			}

			// get schedule options
			$schedules = ProductSchedule::where('product_id', $products->product_id)
				->whereRaw('schedule_date > curdate()')
				->get();
			$scheduleOptions = '';
			if($schedules)
			{
				foreach($schedules as $schedule)
				{
					$scheduleOptions .= '<option value="'.$schedule->product_schedule_id.'">'.$this->scheduleStringDate($schedule->schedule_date).'</option>';
				}
			}
			$data['options'] = $scheduleOptions;

			// get itineraries
			$itineraries = ProductItinerary::where('product_id', $products->product_id)->get();
			$data['itineraries'] = $itineraries;
			$data['product'] = $products;

			return view('product.detail-'.$products->product_template, $data);
		}

		return view('errors.404', $data);
	}

	public function productType($productId, $productType)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		
		$products = Product::join('product_types', 'product_types.product_type_id', 'products.product_type_id')
			->where('product_types.product_type_id', $productId)
			->get();

		return view('errors.404', $data);	
	}

	public function ajaxGetRecomendation()
	{
		return [];
	}

	protected function getDataTour()
	{
		$product = Product::join('tours', 'tours.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.subtitle',
				'products.slug',
				DB::raw('"tour" As product_type'),
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'tours.description',
				'tours.availability_type',
				DB::raw('COALESCE((SELECT MIN(public_price) FROM '.DB::getTablePrefix().'tour_schedules where tour_id= '.DB::getTablePrefix().'tours.tour_id AND schedule_date > curdate()),0) AS product_price')
			])
			->where('products.status', 1)
			->having('product_price','>', 0)
			->paginate(4);
		return $product;
	}

	public function ajaxGetProduct($productType)
	{

		$method = 'getData' . ucfirst($productType);
		$products = call_user_func_array([$this, $method], []);
		return $products;
	}
	
	public function ajaxGetTourAll(Request $request)
	{
		$page = $request->input('tourpage');
		$start = ($page - 1) * 6;

        $data = Product::join('tours','tours.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.subtitle',
				'products.slug',
				'products.star_rating',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'tours.description',
				'tours.default_publish_price AS public_price',
    			DB::raw('(SELECT COUNT(tour_schedule_id) FROM '.DB::getTablePrefix().'tour_schedules where tour_id= '.DB::getTablePrefix().'tours.tour_id AND schedule_date > curdate()) AS product_schedules')
			])
    		->having('product_schedules','>', 0)
			->where('products.product_type_id',1)
			->where('products.status',1)
			->where('tours.default_publish_price','>',0)
			->offset($start)->limit(6)->get();
		
		$totalData = $data->count('products.product_id');
		$paging = intdiv($totalData,6) + 1;
		// $productTour = $data->offset($start)->limit(6)->get();
		$productTour = $data;

		if($productTour){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productTour' => $productTour,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk tour tidak ditemukan',
                'productTour' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}

	public function ajaxGetSouvenirAll(Request $request)
	{
		$page = $request->input('souvenirpage');
		$start = ($page - 1) * 6;

        $data = Product::join('souvenirs','souvenirs.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.slug',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'products.star_rating',
				'souvenirs.souvenir_description',
				'souvenirs.default_publish_price'
			])
			->where('products.product_type_id',2)
			->where('products.status',1);
		
		$totalData = $data->count();
		$paging = intdiv($totalData,6) + 1;
		$productSouvenir = $data->offset($start)->limit(6)->orderBy('default_publish_price')->get();

		if($productSouvenir){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productSouvenir' => $productSouvenir,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk souvenir tidak ditemukan',
                'productSouvenir' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetKulinerAll(Request $request)
	{
		$page = $request->input('kulinerpage');
		$start = ($page - 1) * 6;

        $data = Product::join('culinaries','culinaries.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.slug',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'products.star_rating',
				'culinaries.culinary_description',
				'culinaries.price_range'
			])
			->where('products.product_type_id',3)
			->where('products.status',1);
		
		$totalData = $data->count();
		$paging = intdiv($totalData,6) + 1;
		$productKuliner = $data->offset($start)->limit(6)->orderBy('title')->get();

		if($productKuliner){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productKuliner' => $productKuliner,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk kuliner tidak ditemukan',
                'productKuliner' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetAkomodasiAll(Request $request)
	{
		$page = $request->input('akomodasipage');
		$start = ($page - 1) * 6;

        $data = Product::join('hotels','hotels.product_id','products.product_id')
						->join('partners','partners.partner_id','products.partner_id')
						->select([
							'partners.partner_id',
							'partners.slug',
							DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'partners.company_thumbnail) AS product_thumbnail'),
							'partners.company_name',
							'partners.company_overview'
						])
						->distinct()
						->where('products.product_type_id',4)
						->where('products.status',1);
		
		$totalData = $data->count('partners.partner_id');
		$paging = intdiv($totalData,6) + 1;
		$productAkomodasilist = $data->offset($start)->limit(6)->get();

		if($productAkomodasilist){

			$productAkomodasi = array();
			foreach($productAkomodasilist as $row){
				$nestedData = array();
				$nestedData['product_thumbnail'] = $row->product_thumbnail;
				$nestedData['product_name'] = $row->company_name;
				$nestedData['description'] = $row->company_overview;
				$nestedData['url'] = $row->partner_id . '-' . $row->slug;
				$productAkomodasi[] = $nestedData;
			}
			
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productAkomodasi' => $productAkomodasi,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk Akomodasi tidak ditemukan',
                'productAkomodasi' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetTransportasiAll(Request $request)
	{
		$page = $request->input('transportasipage');
		$start = ($page - 1) * 6;

        $data = Product::join('rent_cars','rent_cars.product_id','products.product_id')
			->join('car_brands','rent_cars.car_brand_id','car_brands.car_brand_id')
			->join('car_models','rent_cars.car_model_id','car_models.car_model_id')
			->select([
				'car_models.car_model_id',
				'car_brands.name AS brand_name',
				'car_models.name AS model_name',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'car_models.car_image) AS product_thumbnail')
			])
			->distinct()
			->where('products.product_type_id',5)
			->where('products.status',1);
		
		$totalData = $data->count('car_models.car_model_id');
		$paging = intdiv($totalData,6) + 1;
		$productTransportasilist = $data->offset($start)->limit(6)->get();

		if($productTransportasilist){

			$productTransportasi = array();
			foreach($productTransportasilist as $row){
				$nestedData = array();
				$nestedData['star_rating'] = $row->star_rating;
				$nestedData['product_thumbnail'] = $row->product_thumbnail;
				$nestedData['product_name'] = $row->brand_name . ' ' . $row->model_name;
				$nestedData['is_driver'] = $row->is_driver;
				$nestedData['is_gas'] = $row->is_gas;
				$nestedData['is_water'] = $row->is_water;
				$nestedData['is_insurance'] = $row->is_insurance;
				$nestedData['is_reschedule'] = $row->is_reschedule;
				$nestedData['is_refund'] = $row->is_refund;
				$nestedData['url'] = $row->car_model_id . '-' . $row->brand_name . '-' . $row->model_name;
				$productTransportasi[] = $nestedData;
			}
			
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productTransportasi' => $productTransportasi,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk transportasi tidak ditemukan',
                'productTransportasi' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetTour(Request $request)
	{
		$tourtag = $request->input('tourtag');
		$datebooking = new DateTime($request->input('datebooking'));
		$quantity = $request->input('quantity');
		$page = $request->input('tourpage');
		$start = ($page - 1) * 6;

        $data = Product::join('product_tags','product_tags.product_id','products.product_id')
						->join('tours','tours.product_id','products.product_id')
						->join('tour_schedules','tour_schedules.tour_id','tours.tour_id')
						->select([
							'products.product_id',
							'products.title',
							'products.subtitle',
							'products.slug',
							'products.star_rating',
							DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
							'tours.description',
							'tour_schedules.public_price'
						])
						->where('products.product_type_id',1)
						->where('products.status',1)						
						->whereDate('tour_schedules.schedule_date','=',$datebooking)
						->where('tour_schedules.outstanding_capacity','>=',intval($quantity))
						->where('tour_schedules.public_price','>',0);
		
		if($tourtag != ''){
			$data = $data->whereIn('product_tags.tag_id',$tourtag);
		}

		$totalData = $data->count('products.product_id');
		$paging = intdiv($totalData,6) + 1;
		$productTour = $data->offset($start)->limit(6)->orderBy('public_price')->get();

		if($productTour){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productTour' => $productTour,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk tour tidak ditemukan',
                'productTour' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}

	public function ajaxGetSouvenir(Request $request)
	{
		$souvenirtag = $request->input('souvenirtag');
		$page = $request->input('souvenirpage');
		$start = ($page - 1) * 6;

        $data = Product::join('product_tags','product_tags.product_id','products.product_id')
			->join('souvenirs','souvenirs.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.slug',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'products.star_rating',
				'souvenirs.souvenir_description',
				'souvenirs.default_publish_price'
			])
			->where('products.product_type_id',2)
			->where('products.status',1)
			->whereIn('product_tags.tag_id',$souvenirtag);
		
		$totalData = $data->count();
		$paging = intdiv($totalData,6) + 1;
		$productSouvenir = $data->offset($start)->limit(6)->orderBy('default_publish_price')->get();

		if($productSouvenir){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productSouvenir' => $productSouvenir,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk souvenir tidak ditemukan',
                'productSouvenir' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetKuliner(Request $request)
	{
		$kulinertag = $request->input('kulinertag');
		$page = $request->input('kulinerpage');
		$start = ($page - 1) * 6;

        $data = Product::join('product_tags','product_tags.product_id','products.product_id')
			->join('culinaries','culinaries.product_id','products.product_id')
			->select([
				'products.product_id',
				'products.title',
				'products.slug',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'products.product_thumbnail) AS product_thumbnail'),
				'products.star_rating',
				'culinaries.culinary_description',
				'culinaries.price_range'
			])
			->where('products.product_type_id',3)
			->where('products.status',1)
			->whereIn('product_tags.tag_id',$kulinertag);
		
		$totalData = $data->count();
		$paging = intdiv($totalData,6) + 1;
		$productKuliner = $data->offset($start)->limit(6)->orderBy('title')->get();

		if($productKuliner){
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productKuliner' => $productKuliner,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk kuliner tidak ditemukan',
                'productKuliner' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetAkomodasi(Request $request)
	{
		$akomodasitag = $request->input('akomodasitag');
		$dateStart = $request->input('datestart');
		$dateEnd = $request->input('dateend');
		$page = $request->input('akomodasipage');
		$start = ($page - 1) * 6;

		$startDate = new DateTime($dateStart);
		$endDate = new DateTime($dateEnd);
		if($startDate != $endDate){
			$interval = $startDate->diff($endDate);
			$totDays = $interval->format('%a') + 1;
		} else {
			$totDays = 1;
		}
		
        $data = Product::join('hotels','hotels.product_id','products.product_id')
						->join('hotel_schedules','hotel_schedules.hotel_id','hotels.hotel_id')
						->join('product_tags','product_tags.product_id','products.product_id')
						->join('partners','partners.partner_id','products.partner_id')
						->select([
							'partners.partner_id',
							'partners.slug',
							DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'partners.company_thumbnail) AS product_thumbnail'),
							'partners.company_name',
							'partners.company_overview'
						])
						->distinct()
						->where('products.product_type_id',4)
						->where('products.status',1)
						->whereDate('hotel_schedules.schedule_date','>=',$startDate)
						->whereDate('hotel_schedules.schedule_date','<=',$endDate)
						->where('hotel_schedules.public_price','>',0);
						
		if($akomodasitag != ''){
			$data = $data->whereIn('product_tags.tag_id',$akomodasitag);
		}

		$totalData = $data->count('partners.partner_id');
		$paging = intdiv($totalData,6) + 1;
		$productAkomodasilist = $data->offset($start)->limit(6)->get();

		if($productAkomodasilist){

			$productAkomodasi = array();
			foreach($productAkomodasilist as $row){
				
				$minPublicPrice = HotelSchedule::join('hotels','hotels.hotel_id','hotel_schedules.hotel_id')
													->join('products','products.product_id','hotels.product_id')
													->join('partners','partners.partner_id','products.partner_id')
													->select([
														'hotel_schedules.public_price'
													])
													->where('partners.partner_id',$row->partner_id)
													->where('products.product_type_id',4)
													->where('products.status',1)
													->whereDate('hotel_schedules.schedule_date','>=',$startDate)
													->whereDate('hotel_schedules.schedule_date','<=',$endDate)
													->where('hotel_schedules.public_price','>',0)
													->orderBy('hotel_schedules.public_price')
													->first();

				$nestedData = array();
				$nestedData['product_thumbnail'] = $row->product_thumbnail;
				$nestedData['product_name'] = $row->company_name;
				$nestedData['description'] = $row->company_overview;
				$nestedData['price'] = number_format($minPublicPrice->public_price,0,",",".");
				$nestedData['url'] = $row->partner_id . '-' . $row->slug;
				$productAkomodasi[] = $nestedData;
			}
			
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productAkomodasi' => $productAkomodasi,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk Akomodasi tidak ditemukan',
                'productAkomodasi' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
	
	public function ajaxGetTransportasi(Request $request)
	{
		$dateStart = $request->input('datestart');
		$dateEnd = $request->input('dateend');
		$quantity = $request->input('quantity');
		$driver = $request->input('driver');
		$page = $request->input('transportasipage');
		$start = ($page - 1) * 6;

		$startDate = new DateTime($dateStart);
		$endDate = new DateTime($dateEnd);
		if($startDate != $endDate){
			$interval = $startDate->diff($endDate);
			$totDays = $interval->format('%a') + 1;
		} else {
			$totDays = 1;
		}
		
        $data = Product::join('rent_cars','rent_cars.product_id','products.product_id')
			->join('rent_car_schedules','rent_car_schedules.rent_car_id','rent_cars.rent_car_id')
			->join('partners','partners.partner_id','products.partner_id')
			->join('car_brands','rent_cars.car_brand_id','car_brands.car_brand_id')
			->join('car_models','rent_cars.car_model_id','car_models.car_model_id')
			->select([
				'products.product_id',
				'products.slug',
				'products.star_rating',
				'car_brands.name AS brand_name',
				'car_models.name AS model_name',
				DB::raw('CONCAT("'.config('constants.UPLOAD_PATH').'", '.DB::getTablePrefix().'car_models.car_image) AS product_thumbnail'),
				'partners.company_name',
				'rent_cars.rent_car_id',
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
			->where('rent_cars.is_driver',$driver)
			->whereDate('rent_car_schedules.schedule_date','>=',$startDate)
			->whereDate('rent_car_schedules.schedule_date','<=',$endDate)
			->where('rent_car_schedules.outstanding_capacity','>=',$quantity)
			->where('rent_car_schedules.public_price','>',0);
		
		$totalData = $data->count('products.product_id');
		$paging = intdiv($totalData,6) + 1;
		$productTransportasilist = $data->offset($start)->limit(6)->get();

		if($productTransportasilist){

			$productTransportasi = array();
			foreach($productTransportasilist as $row){
				
				$totPublicPrice = RentCarSchedule::where('rent_car_id',$row->rent_car_id)
													->whereDate('schedule_date','>=',$startDate)
													->whereDate('schedule_date','<=',$endDate)
													->where('public_price','>',0)
													->sum('public_price');

				$nestedData = array();
				$nestedData['company_name'] = $row->company_name;
				$nestedData['star_rating'] = $row->star_rating;
				$nestedData['product_thumbnail'] = $row->product_thumbnail;
				$nestedData['product_name'] = $row->brand_name . ' ' . $row->model_name;
				$nestedData['is_driver'] = $row->is_driver;
				$nestedData['is_gas'] = $row->is_gas;
				$nestedData['is_water'] = $row->is_water;
				$nestedData['is_insurance'] = $row->is_insurance;
				$nestedData['is_reschedule'] = $row->is_reschedule;
				$nestedData['is_refund'] = $row->is_refund;
				$nestedData['price'] = number_format(($totPublicPrice/$totDays),0,",",".");
				$nestedData['url'] = $row->product_id . '-' . $row->slug . '?start=' . $dateStart . '&end=' . $dateEnd . '&qty=' . $quantity . '&driver=' . $driver;
				$productTransportasi[] = $nestedData;
			}
			
			$results = 
            [
                'status' => 'success',
                'message' => 'Success Add Data',
                'productTransportasi' => $productTransportasi,
                'totalData' => $totalData,
                'paging' => $paging,
                'page' => $page
            ];
		} else {
			$results = 
            [
                'status' => 'error',
                'message' => 'Produk transportasi tidak ditemukan',
                'productTransportasi' => '',
                'totalData' => '',
                'paging' => '',
                'page' => ''
            ];
		}
        
        return response($results);
	}
}
