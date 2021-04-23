<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Mail\MailNotify;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\EmailRepository;
use App\Repositories\RajaOngkirRepository;
use App\Models\Destination;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Province;
use App\Models\City;
use App\Models\SubDistrict;
use App\Models\Message;
use App\Helpers\FunctionsHelper;
use DB;


class HomeController extends BaseController
{

	protected $_config = null;
	protected $_func = null;

	protected function buildHtmlSlickSliderNavigation($sliders)
	{
		$carouselIndicators = '';
		$active = 'active';

		$carouselIndicators = '<div class="new-slide-nav">
		<div class="container">
			<div class="row">';
		$index = 0;
		foreach ($sliders as $key => $slider) {
			if (!$slider['disabled']) {
				if ($index <= 3) {
					$carouselIndicators .= '
							<div class="slide-nav-li col nav-' . ($key + 1) . ' ' . $active . '">
								<a href="javascript:void(0)" class="' . $active . ' text-bold">' . (empty($slider['title'])?'Home Page':$slider['title']) . '</a>
							</div>';
				}
				$index++;
				$active = '';
			}
		}
		$carouselIndicators .= '</div></div></div>';

		$html = $carouselIndicators;
		return $html;
	}

	protected function buildHtmlSlickSlider($sliders)
	{
		$carouselInner = '';
		$imagePath = config('constants.UPLOAD_PATH');
		$active = 'active';
		$index = 0;
		foreach ($sliders as $key => $slider) {
			if (!$slider['disabled']) {
				if ($index <= 3) {
					$carouselInner .= '<div class="banner-cover">
								<div class="bg-slider"></div>
								<div class="img-slider" data-parallax="false" style="background-image: url(' . $imagePath . $slider['image'] . ')">
								</div>
								<div class="capt-slider">
									<div class="motto text-center">
										<h2 class="title-capt">
											' . $slider['title'] . '
										</h2>
										<h3>' . $slider['description'] . '</h3>
										<br />';


					if (!empty($slider['button_caption'])) {
						$carouselInner .= '<a href="' . $slider['button_link'] . '" class="btn btn-success new-btn-primary" tabindex="0">' . $slider['button_caption'] . '<span class="ml-2">❯</span></a>';
					}
				}

				$index++;
				$carouselInner .= '</div></div></div>';
				$active = '';
			}
		}

		$html = $carouselInner;
		return $html;
	}
	protected function buildHtmlSlider($sliders)
	{
		$carouselIndicators = '';
		$carouselInner = '';
		$imagePath = config('constants.UPLOAD_PATH');
		$active = 'active';
		foreach ($sliders as $key => $slider) {
			if (!$slider['disabled']) {
				$carouselIndicators .= '<li data-target="#banner-home" data-slide-to="' . $key . '" class="' . $active . '"></li>';
				$carouselInner .= '<div class="carousel-item ' . $active . '">
	                <div class="bg-slider"></div>
	                <img class="img-slide" src="' . $imagePath . $slider['image'] . '" alt="VISITDAIRI">
	                <div class="carousel-caption d-none d-md-block">
	                    <div class="caption"> <h3>' . $slider['title'] . '</h3><p>' . $slider['description'] . '</p></div>';
				if (!empty($slider['button_caption'])) {
					$carouselInner .= '<div class="wrap_btn"><div class="row"><div class="col-md-4"><a class="default_btn" href="' . $slider['button_link'] . '">' . $slider['button_caption'] . '</a></div></div></div>';
				}

				$carouselInner .= '</div></div>';
				$active = '';
			}
		}

		$html = '<ol class="carousel-indicators">' . $carouselIndicators . '</ol> <div class="carousel-inner">' . $carouselInner . '</div>';
		return $html;
	}

	protected function buildHtmlProduct()
	{
		$productTab = '';
		$productContent = '';

		$ptype = new ProductType;
		$productTypes = $ptype->getList(true);
		if ($productTypes) {

			$active = 'active';
			$activeContent = 'in active show';
			foreach ($productTypes as $type) {
				// Load product
				$product = new Product;
				$products = $product->getRecomendation($type['slug'], 5);

				$productTab .= '<li class="' . $active . '"><a data-toggle="tab" href="#' . $type['slug'] . '" class="' . $active . '">' . $type['name'] .
					'<input type="hidden" name="' . $type['slug'] . '" value="' . $type['description'] . '"></a></li>';
				$productContent .= '<div id="' . $type['slug'] . '" class="tab-pane fade ' . $activeContent . '">' .
					'    <div class="owl-carousel owl-theme" id="slider-wisata">';

				if (sizeof($products) > 0) {
					foreach ($products as $item) {
						$Url = route('product' . $type['slug'], [$item['product_id'], $item['slug']]);
						$imagePath = config('constants.UPLOAD_PATH');
						$imageSrc = $imagePath . $item['product_thumbnail'];
						$productContent .= '<div class="item">' .
							'	<div class="wrap_item">' .
							'		<div class="bg-slide-owl"></div>' .
							'		<img class="img-responsive" src="' . $imageSrc . '" alt="img-product" />' .
							'		<a href="' . $Url . '" class="up-btn">Pesan</a>' .
							'       <div class="caption-product">' .
							'       <div class="title-product">' .
							'       	<h5>' . $item['title'] . '</h5>' .
							'       </div>' .
							'   	</div>' .
							'	</div>' .
							'</div>';
					}
				}


				$productContent .= '</div>' .
					'<div class="all-product">' .
					'<a href="product/all/' . $type['slug'] . '" class="default_btn">LIHAT SEMUA ' . $type['name'] . '</a>' .
					'</div>' .
					'</div>';
				$active = '';
				$activeContent = '';
			}
		}

		return [$productTab, $productContent];
	}

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs;
		$this->_func = $functions;
	}

	public function index(RajaOngkirRepository $ro)
	{

		// get config
		$config = $this->_config->get();
		$data['config'] = $config;

		// get destination
		$destination = new Destination;
		$destinations = $destination->getList();
		$destinations = $this->_func->sort($destinations, 'random');
		$data['destinations'] = $destinations;

		// get banner 
		$banner = $this->_config->get('homeslider', 'banner');
		$data['htmlSlider'] = '';
		// $data['htmlSlider'] = $this->buildHtmlSlider($banner);

		//add new slider by jumri
		$data['htmlSlickSlider'] = $this->buildHtmlSlickSlider($banner);
		$data['htmlSlickSliderNavigation'] = $this->buildHtmlSlickSliderNavigation($banner);

		list($productTab, $productContent) = $this->buildHtmlProduct();
		$data['productTab'] = $productTab;
		$data['productContent'] = $productContent;
		// get section
		$section = $this->_config->get('homesection', 'homesection');
		$data['section'] = $section;

		$data['auth'] = new CustomerRepository;

		return view('home.index', $data);
	}

	public function ajaxGetProvince()
	{
		$province = Province::all();
		// $data = [];
		// if($provinces)
		// {
		// 	foreach($provinces as $prov)
		// 	{
		// 		$data[] = [
		// 			'id'=>$prov->province_id,
		// 			'name'=>$prov->name
		// 		];
		// 	}
		// }

		$results =
			[
				'status' => 'success',
				'message' => 'success',
				'province' => $province
			];

		return response($results);
	}

	public function ajaxGetCity(Request $request)
	{
		$province = $request->province;

		$city = City::where('province_id', $province)->orderBy('city_name')->get();
		// if($province) {
		// 	$city->where('province_id', $province);
		// }
		// $cities = $city->get();
		// $data = [];
		// if($cities)
		// {
		// 	foreach($cities as $c)
		// 	{
		// 		$data[] = [
		// 			'id'=>$c->city_id,
		// 			'name'=>$c->city_name
		// 		];
		// 	}
		// }

		$results =
			[
				'status' => 'success',
				'message' => 'success',
				'city' => $city
			];

		return response($results);
	}

	public function ajaxGetSubdistrict(Request $request)
	{
		$city = $request->city;

		$subdistrict = SubDistrict::where('city_id', $city)->orderBy('subdistrict_name')->get();
		// if($city) {
		// 	$subdistrict->where('city_id', $city);
		// }
		// $subdistricts = $subdistrict->get();
		// $data = [];
		// if($subdistricts)
		// {
		// 	foreach($subdistricts as $c)
		// 	{
		// 		$data[] = [
		// 			'id'=>$c->subdistrict_id,
		// 			'name'=>$c->subdistrict_name
		// 		];
		// 	}
		// }

		$results =
			[
				'status' => 'success',
				'message' => 'success',
				'subdistrict' => $subdistrict
			];

		return response($results);
	}

	public function refreshCache()
	{
		$this->_config->refresh();
		return true;
	}

	public function contactUs(Request $request)
	{

		$validator = \Validator::make($request->all(), [
			'name' => 'required|min:3',
			'phone' => 'required',
			'email' => 'required|email',
			'subject' => 'required',
			'custmessage' => 'required'
		], [
			'name.required' => 'Nama wajib diisi!',
			'name.min' => 'Nama minimal 3 karakter',
			'phone.required' => 'Telepon wajib diisi!',
			'email.required' => 'Email wajib diisi!',
			'email.email' => 'Format penulisan email tidak sesuai',
			'subject.required' => 'Silahkan cantumkan subjek pesan',
			'custmessage.required' => 'Silahkan masukkan pesan anda'
		]);

		// return invalid request
		if ($validator->fails()) {
			return response()->json($validator->errors(), 422);
		}

		$dataMessage = [
			'name' => $request->name,
			'phone' => $request->phone,
			'email' => $request->email,
			'subject' => $request->subject,
			'message' => $request->custmessage,
			'created_at' => date("Y-m-d H:i:s")
		];
		Message::insert($dataMessage);

		return [
			'status' => 'success',
			'message' => 'Pesan anda telah dikirimkan kepada admin <b>visitdairi.com</b>. Terima Kasih.'
		];
	}
}
