<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\MidtransRepository;
use App\Repositories\CartRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Cart;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\PayChannel;
use App\Models\Trans;
use App\Models\TransDetail;
use App\Models\TransPayment;
use App\Models\RentCarSchedule;
use App\Models\Courier;
use DB;
use DateTime;

class TransactionController extends BaseController
{

	protected $_func;
	protected $_config;

	const PRODUCT_TOUR = 1;
	const PRODUCT_SOUVENIR = 2;

	const productType = ['tour','souvenir','kuliner','akomodasi','transportasi'];
	const cartTTL = 259200;

	protected function sessionCart()
	{
		$session = session('__cart');
		if(!$session)
		{
			$uuid = Uuid::uuid4()->toString();
			session(['__cart'=>$uuid]);
		}
		return session('__cart');
	}

	protected function addCart($param, $details)
	{
		$sessionCart = $this->sessionCart();
	
		$datetime = date('Y-m-d H:i:s');

		if($param['product_type'] == 'akomodasi')
		{
			$param['product_id'] = $param['partner_id'];
			$param['quantity'] = 1;
		}

		$carts = Cart::where('cart_key',$sessionCart)
			->where('product_type', $param['product_type'])
			->where('product_id', $param['product_id'])
			->first();

		if($carts)
		{
			Cart::where('cart_id', $carts->cart_id)->update([
				'quantity'=> intval($param['quantity']),
				'product_type' => $param['product_type'],
				'cart_param' => serialize($details),
				'updated_at'=>$datetime
			]);
			return $sessionCart;
		}
		$uuid = Uuid::uuid4()->toString();
		$data = [
			'cart_id' => $uuid,
		 	'cart_key' => $sessionCart,
		 	'product_id' => $param['product_id'],
		 	'product_type' => $param['product_type'],
		 	'quantity' => intval($param['quantity']),
		 	'cart_param' => serialize($details),
		 	'created_at' => $datetime,
		];
		Cart::insert($data);
		return $sessionCart;
	}

	protected function genInvoice()
	{
		//Ex: INV-20201123-0001
		$invoice = 'INV-' . date('Ymd') . '-';
		$num = 1;
		$inv = Trans::select('invoice_number')
			->where('invoice_number','like',$invoice.'%')
			->orderBy('invoice_number', 'DESC')
			->first();
		if($inv)
		{
			$invoiceNumber = $inv->invoice_number;
			$invoiceNumber = str_replace($invoice, '', $invoiceNumber);
			$invoiceNumber = intval($invoiceNumber); 
			$num = $invoiceNumber + 1;
		}
		return $invoice . str_pad($num, 4, '0', STR_PAD_LEFT);
	}

	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_func = $functions;
		$this->_config = $configs;
	}

	public function formOrder($cartKey, Request $request, CartRepository $cart)
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

		$data['product'] = unserialize($carts->param2);
		$data['auth'] = $auth;
		if($auth->logged())
		{
			$data['customer'] = $auth->getCustomer(true);
		}

		if(strtolower($carts->product_type) == 'souvenir')
		{
			$couriers = Courier::where('disabled', 0)->get();
			$data['couriers'] = $couriers;
			$productPrice = (int)$cartData['product_price'];
			$subTotalPrice = (int)$cartData['quantity'] * (int)$cartData['product_price'];
			$totalPrice = (int)$subTotalPrice + (int)$cartData['shipment_courier']['courier_cost']; 
			$data['productPrice'] = number_format($productPrice, 0, '.', '.');
			$data['courierCost'] = number_format((int)$cartData['shipment_courier']['courier_cost'], 0, '.', '.');
			$data['subTotalPrice'] = number_format($subTotalPrice, 0, '.', '.');
			$data['totalPrice'] = number_format($totalPrice, 0, '.', '.');
		} elseif(strtolower($carts->product_type) == 'akomodasi')
		{
			$totalPrice = 0;
			$rooms = [];
			foreach($cartData['rooms'] as $room)
			{
				$subtotal = $room['product_price'] * $room['quantity'];
				$totalPrice += $subtotal;
				$rooms[$room['product_id']]['title'] = $room['title'];
				$rooms[$room['product_id']]['quantity'] = $room['quantity'];

				if(!isset($rooms[$room['product_id']]['product_price']))
				{
					$rooms[$room['product_id']]['product_price'] = 0;
				}
				$rooms[$room['product_id']]['product_price'] += (int)$room['product_price'];
			}

			$data['checkin'] = $this->_func->scheduleStringDate($cartData['checkin']);
			$data['checkout'] = $this->_func->scheduleStringDate($cartData['checkout']);
			$data['days'] = $this->_func->nightOfDays($cartData['checkin'], $cartData['checkout']);
			$data['rooms'] = $rooms;
			$data['totalPrice'] = number_format($totalPrice, 0, '.', '.');
		}
		
		return view('order.form-'.$carts->product_type, $data);
	}

	public function checkout()
	{
		$config = $this->_config->get();
		$data['config'] = $config;

		return view('order.checkout', $data);
	}

	public function confirmation()
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		return view('layouts.coming-soon', $data);
	}

	public function checkOrder()
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		return view('order.check-order', $data);
	}

	public function retrieve($orderId, Request $request)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		if($request->trans_id != $orderId)
		{
			return redirect()->route('checkOrder');
		}
		$orderId = strtoupper($orderId);
		$data['orderId'] = $orderId;

		$trans = Trans::join('trans_det', 'trans_det.trans_id', 'trans.trans_id')
			->where('trans_code', $orderId)
			->first();
		
		if($trans)
		{
			// get detail
			$transDetail = !empty($trans->product_detail) ? unserialize($trans->product_detail) : '';	
			$transDetail['title'] = isset($transDetail['title']) ? $transDetail['title'] : $transDetail['company_name'];	
			$transDetail['subtitle'] = isset($transDetail['subtitle']) ? $transDetail['subtitle'] : '';	
			$transDetail['product_thumbnail'] = isset($transDetail['product_thumbnail']) ? $transDetail['product_thumbnail'] : $transDetail['partner_thumbnail'];
			if($trans->product_type_id != 2){
				$transDetail['schedule_date'] = isset($transDetail['schedule_date']) ? $transDetail['schedule_date'] : date('d M Y', strtotime($transDetail['checkin'])) . ' - ' . date('d M Y', strtotime($transDetail['checkout']));
			}			
			$data['transDetail'] = $transDetail;

			// get payment
			$payment = TransPayment::select('trans_payment.total_payment','pay_channels.name As payment_channel_name')
				->join('pay_channels', 'pay_channels.pay_channel_id', 'trans_payment.pay_channel_id')
				->where('trans_id', $trans->trans_id)
				->first();

			$data['payment'] = $payment;
		}
		$data['trans'] = $trans;

		return view('order.retrieve-order', $data);
	}

	public function submitCheckout($cartKey, Request $request)
	{
		$validator = \Validator::make($request->all(),[
			'email'=> 'required',
			'nama'=> 'required|min:3',
			'state'=> 'required',
			'telp'=> 'required|min:5',
		],[
			'email.required' => 'Email wajib diisi!',
			'nama.required'=> 'Nama wajib diisi!',
			'state.required'=> 'Sapaan wajib diisi!',
			'telp.required'=> 'Telepon wajib diisi!',
		]);

		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}
		
		$carts = Cart::where('cart_key', $cartKey)->first();
		if(!$carts)
		{
			return $this->_func->resJSON(200,0,[], 'Produk yang anda cari sudah tidak tersedia');
		}
		$cartData = unserialize($carts->cart_param);

		// check login
		$auth = new CustomerRepository;
		if($auth->logged())
		{
			$customerId = $auth->getId();
		} else {
			// REGISTER NEW MEMBER
			$customerId = 0;
		}

		switch(strtolower($carts->product_type))
		{
			case 'tour':

				$products = Product::join('tours', 'tours.product_id', 'products.product_id')
					->select([
						'products.product_id',
						'products.partner_id',
						'products.product_type_id',
						'products.title',
						'products.subtitle',
						'products.slug',
						'products.product_thumbnail',
						'tours.*',
						DB::raw('(SELECT COUNT(tour_schedule_id) FROM '.DB::getTablePrefix().'tour_schedules WHERE outstanding_capacity>='.$carts->quantity.' AND tour_schedule_id="'.$cartData['tour_schedule_id'].'") AS total_schedule')
					])
					->where('products.product_id', $carts->product_id)
					->having('total_schedule','>',0)
					->first();
				if(!$products)
				{
					return $this->_func->resJSON(200,0,[], 'Produk yang anda cari sudah tidak tersedia/stok habis');
				}

				//// TRANSACTION TOUR
				$totalDisc = 0;
				$totalShipmentFee = 0;
				$paymentFee = 0;

				// PRODUCT PRICE
				$subtotal = $cartData['quantity'] * $cartData['product_price'];
				$totalProductPrice = 1 * $subtotal;

				// PRODUCT TYPE ID
				$productTypeId = $products->product_type_id;

				// PARTNER PRICE
				$subtotalPartnerPrice = $cartData['quantity'] * $cartData['partner_price'];
				$totalPartnerPrice = $subtotalPartnerPrice;

				// COMMISSION
				$subtotalCommission = 0;
				if($products->cooperation_type == 'COMMISSION')
				{
					if($products->commission_type == 'PERCENT')
					{
						$subtotalCommission = $subtotal * $products->commission_value / 100;
					} else {
						$subtotalCommission = $products->commission_value;
					}
				}
				$totalCommission = $subtotalCommission;

				// MARKUP
				$subtotalMarkup = 0;
				if(!empty($products->markup_type) && !empty($products->markup_value))
				{
					if($products->markup_type == 'PERCENT')
					{
						$subtotalMarkup = $subtotal * $products->markup_value / 100;
					} else {
						$subtotalMarkup = $products->markup_value;
					}
				}
				$totalMarkup = $subtotalMarkup;

				// TOTAL PAYMENT
				$totalPayment = ($totalProductPrice + $totalShipmentFee + $paymentFee) - $totalDisc;

				// generate transaction code
				$transCode = $this->_func->uniqueCode(8);

				$tranData = [
					'invoice_number' => $this->genInvoice(),
					'invoice_date' => date('Y-m-d H:i:s'),
					'trans_code' => $transCode,
					'customer_id' => $customerId,
					'customer_name' => $request->nama,
					'customer_phone' => $request->telp,
					'customer_email' => $request->email,
					'total_product_price' => $totalProductPrice,
					'total_disc' => $totalDisc,
					'total_shipment_fee' => $totalShipmentFee,
					'payment_fee' => $paymentFee,
					'total_payment' => $totalPayment,
					'total_commission' => $totalCommission,
					'total_markup' => $totalMarkup,
					'total_partner_price' => $totalPartnerPrice,
					'status' => 1,
					'timelimit' => date('Y-m-d H:i:s', strtotime('+1 days')),
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];

				$transId = Trans::insertGetId($tranData);

				// transaction detail
				$transDetail = [
					'trans_id' => $transId,
					'product_id' => $carts->product_id,
					'product_type_id' => $productTypeId,
					'product_detail' => $carts->cart_param,
					'price' => $cartData['product_price'],
					'qty' => $cartData['quantity'],
					'subtotal' => $subtotal,
					'subtotal_commission' => $subtotalCommission,
					'subtotal_markup' => $subtotalMarkup,
					'subtotal_partner_price' => $subtotalPartnerPrice,
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];

				$success = TransDetail::insert($transDetail); 

				break;
			case 'souvenir':

				$products = Product::join('souvenirs', 'souvenirs.product_id', 'products.product_id')
					->select([
						'products.product_id',
						'products.partner_id',
						'products.product_type_id',
						'products.title',
						'products.subtitle',
						'products.slug',
						'products.product_thumbnail',
						'souvenirs.*',
					])
					->where('products.product_type_id', 2)
					->where('products.product_id', $carts->product_id)
					->first();
				
				if(!$products)
				{
					return $this->_func->resJSON(200,0,[], 'Produk yang anda cari sudah tidak tersedia/stok habis');
				}

				//// TRANSACTION TOUR
				$totalDisc = 0;
				$totalShipmentFee = 0;
				$paymentFee = 0;

				// PRODUCT PRICE
				$subtotal = $cartData['quantity'] * $cartData['product_price'];
				$totalProductPrice = 1 * $subtotal;

				// PRODUCT TYPE ID
				$productTypeId = $products->product_type_id;

				// PARTNER PRICE
				$subtotalPartnerPrice = $cartData['quantity'] * $cartData['partner_price'];
				$totalPartnerPrice = $subtotalPartnerPrice;

				// COMMISSION
				$subtotalCommission = 0;
				if($products->cooperation_type == 'COMMISSION')
				{
					if($products->commission_type == 'PERCENT')
					{
						$subtotalCommission = $subtotal * $products->commission_value / 100;
					} else {
						$subtotalCommission = $products->commission_value;
					}
				}
				$totalCommission = $subtotalCommission;

				// MARKUP
				$subtotalMarkup = 0;
				if(!empty($products->markup_type) && !empty($products->markup_value))
				{
					if($products->markup_type == 'PERCENT')
					{
						$subtotalMarkup = $subtotal * $products->markup_value / 100;
					} else {
						$subtotalMarkup = $products->markup_value;
					}
				}
				$totalMarkup = $subtotalMarkup;

				// TOTAL PAYMENT
				$totalPayment = ($totalProductPrice + $totalShipmentFee + $paymentFee) - $totalDisc;

				// generate transaction code
				$transCode = $this->_func->uniqueCode(8);

				$tranData = [
					'invoice_number' => $this->genInvoice(),
					'invoice_date' => date('Y-m-d H:i:s'),
					'trans_code' => $transCode,
					'customer_id' => $customerId,
					'customer_name' => $request->nama,
					'customer_phone' => $request->telp,
					'customer_email' => $request->email,
					'total_product_price' => $totalProductPrice,
					'total_disc' => $totalDisc,
					'total_shipment_fee' => $totalShipmentFee,
					'payment_fee' => $paymentFee,
					'total_payment' => $totalPayment,
					'total_commission' => $totalCommission,
					'total_markup' => $totalMarkup,
					'total_partner_price' => $totalPartnerPrice,
					'status' => 1,
					'timelimit' => date('Y-m-d H:i:s', strtotime('+1 days')),
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];
				$totalShipmentFee = (int)$cartData['shipment_courier']['courier_cost'];
				$tranData['shipment_status'] = 0;
				$tranData['shipment_note'] = '';
				$tranData['total_shipment_fee'] = $totalShipmentFee;
				$transId = Trans::insertGetId($tranData);

				// transaction detail
				$transDetail = [
					'trans_id' => $transId,
					'product_id' => $carts->product_id,
					'product_type_id' => $productTypeId,
					'product_detail' => $carts->cart_param,
					'price' => $cartData['product_price'],
					'qty' => $cartData['quantity'],
					'subtotal' => $subtotal,
					'subtotal_commission' => $subtotalCommission,
					'subtotal_markup' => $subtotalMarkup,
					'subtotal_partner_price' => $subtotalPartnerPrice,
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];

				$success = TransDetail::insert($transDetail); 

				break;
			case 'kuliner':
			
				$products = Product::find($carts->product_id);

				// generate transaction code
				$transCode = $this->_func->uniqueCode(8);
		
				$tranData = [
					'trans_code' => $transCode,
					'customer_id' => $customerId,
					'customer_name' => $request->nama,
					'customer_phone' => $request->telp,
					'customer_email' => $request->email,
					'total_product_price' => 0,
					'total_disc' => 0,
					'total_shipment_fee' => 0,
					'payment_fee' => 0,
					'total_payment' => 0,
					'total_commission' => 0,
					'total_markup' => 0,
					'total_partner_price' => 0,
					'status' => 1,
					'created_at' => date('Y-m-d H:i:s')
				];
		
				$transId = Trans::insertGetId($tranData);
		
				// transaction detail
				$transDetail = [
					'trans_id' => $transId,
					'product_id' => $carts->product_id,
					'product_type_id' => $products->product_type_id,
					'product_detail' => $carts->cart_param,
					'price' => 0,
					'qty' => $cartData['quantity'],
					'subtotal' => 0,
					'subtotal_commission' => 0,
					'subtotal_markup' => 0,
					'subtotal_partner_price' => 0,
					'created_at' => date('Y-m-d H:i:s')
				];
		
				$success = TransDetail::insert($transDetail); 
	
				break;
			case 'transportasi':

				$products = Product::find($carts->product_id);

				$totPrice = $cartData['tot_price'];
				$totCommission = 0;
				$totMarkup = 0;
				$totPartnerPrice = 0;

				if(!empty($products->rentcar->commission_type) && !empty($products->rentcar->commission_value)){
					if($products->rentcar->commission_type == 'PERCENT'){
						$totCommission = $totPrice * ($products->rentcar->commission_value/100);
					} else if($products->rentcar->commission_type == 'AMOUNT'){
						$totCommission = $products->rentcar->commission_value;
					}
				}				

				if(!empty($products->rentcar->markup_type) && !empty($products->rentcar->markup_value)){
					if($products->rentcar->markup_type == 'PERCENT'){
						$totMarkup = $totPrice * ($products->rentcar->markup_value/100);
					} else if($products->rentcar->markup_type == 'AMOUNT'){
						$totMarkup = $products->rentcar->markup_value;
					}
				}				

				$totPartnerPrice = $totPrice - $totCommission - $totMarkup;

				// generate transaction code
				$transCode = $this->_func->uniqueCode(8);
		
				$tranData = [
					'invoice_number' => $this->genInvoice(),
					'invoice_date' => date('Y-m-d H:i:s'),
					'trans_code' => $transCode,
					'customer_id' => $customerId,
					'customer_name' => $request->nama,
					'customer_phone' => $request->telp,
					'customer_email' => $request->email,
					'total_product_price' => $cartData['tot_price'],
					'total_disc' => 0,
					'total_shipment_fee' => 0,
					'payment_fee' => 0,
					'total_payment' => $cartData['tot_price'],
					'total_commission' => $totCommission,
					'total_markup' => $totMarkup,
					'total_partner_price' => $totPartnerPrice,
					'status' => 1,
					'created_at' => date('Y-m-d H:i:s')
				];
		
				$transId = Trans::insertGetId($tranData);
		
				// transaction detail
				$transDetail = [
					'trans_id' => $transId,
					'product_id' => $cartData['product_id'],
					'product_type_id' => $products->product_type_id,
					'product_detail' => $carts->cart_param,
					'price' => $cartData['price'],
					'qty' => $cartData['quantity'],
					'subtotal' => $cartData['tot_price'],
					'subtotal_commission' => $totCommission,
					'subtotal_markup' => $totMarkup,
					'subtotal_partner_price' => $totPartnerPrice,
					'created_at' => date('Y-m-d H:i:s')
				];
		
				$success = TransDetail::insert($transDetail); 
	
				break;
			case 'akomodasi':
				//// TRANSACTION AKOMODASI
				$totalDisc = 0;
				$totalShipmentFee = 0;
				$paymentFee = 0;
				$totalQty = 0;
				$totalProductPrice = 0;
				$totalPartnerPrice = 0;
				$productIds = array_column($cartData['rooms'], 'product_id');

				foreach($cartData['rooms'] as $room)
				{
					// PRODUCT PRICE
					$subtotal = (int)$room['quantity'] * (int)$room['product_price'];
					$totalProductPrice += $subtotal;

					// PARTNER PRICE
					$subtotalPartnerPrice = (int)$room['quantity'] * (int)$room['partner_price'];
					$totalPartnerPrice += $subtotalPartnerPrice;

					$totalQty += (int)$room['quantity'];
				}
				
				// PRODUCT TYPE ID
				$productTypeId = 4;

				$products = Product::join('hotels', 'hotels.product_id', 'products.product_id')
					->join('hotel_schedules', 'hotel_schedules.hotel_id', 'hotels.hotel_id')
					->select([
						'hotels.cooperation_type',
						'hotels.commission_type',
						'hotels.commission_value',
						'hotels.markup_type',
						'hotels.markup_value'
					])
					->whereIn('products.product_id', $productIds)
					->get();

				$subtotalCommission = 0;
				$subtotalMarkup = 0;

				if(sizeof($products) > 0)
				{
					foreach($products as $product)
					{
						// COMMISSION
						if($product->cooperation_type == 'COMMISSION')
						{
							if($product->commission_type == 'PERCENT')
							{
								$subtotalCommission += $subtotal * $product->commission_value / 100;
							} else {
								$subtotalCommission += $product->commission_value;
							}
						}
						
						// MARKUP
						if(!empty($product->markup_type) && !empty($product->markup_value))
						{
							if($product->markup_type == 'PERCENT')
							{
								$subtotalMarkup += $subtotal * $product->markup_value / 100;
							} else {
								$subtotalMarkup += $product->markup_value;
							}
						}
						
					}
					
				}
				$totalCommission = $subtotalCommission;
				$totalMarkup = $subtotalMarkup;

				// TOTAL PAYMENT
				$totalPayment = ($totalProductPrice + $totalShipmentFee + $paymentFee) - $totalDisc;

				// generate transaction code
				$transCode = $this->_func->uniqueCode(8);

				$tranData = [
					'invoice_number' => $this->genInvoice(),
					'invoice_date' => date('Y-m-d H:i:s'),
					'trans_code' => $transCode,
					'customer_id' => $customerId,
					'customer_name' => $request->nama,
					'customer_phone' => $request->telp,
					'customer_email' => $request->email,
					'total_product_price' => $totalProductPrice,
					'total_disc' => $totalDisc,
					'total_shipment_fee' => $totalShipmentFee,
					'payment_fee' => $paymentFee,
					'total_payment' => $totalPayment,
					'total_commission' => $totalCommission,
					'total_markup' => $totalMarkup,
					'total_partner_price' => $totalPartnerPrice,
					'status' => 1,
					'timelimit' => date('Y-m-d H:i:s', strtotime('+1 days')),
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];

				$transId = Trans::insertGetId($tranData);

				// transaction detail
				$transDetail = [
					'trans_id' => $transId,
					'product_id' => $carts->product_id,
					'product_type_id' => $productTypeId,
					'product_detail' => $carts->cart_param,
					'price' => $totalProductPrice,
					'qty' => $totalQty,
					'subtotal' => $totalProductPrice,
					'subtotal_commission' => $subtotalCommission,
					'subtotal_markup' => $subtotalMarkup,
					'subtotal_partner_price' => $subtotalPartnerPrice,
					'created_at' => date('Y-m-d H:i:s'),
					'modified_at' => date('Y-m-d H:i:s')
				];

				$success = TransDetail::insert($transDetail); 
				break;
			default:
				$success = false;
		}

		if($success)
		{
			@Cart::where('cart_key', $cartKey)->delete();
			return $this->_func->resJSON(200,1,[
				'order_id' => $transId,
				'order_code' => $transCode
			], '');
		}
		return $this->_func->resJSON(200,0,[], 'Transaksi gagal!');

	}

	private function validatorRule($productType)
	{
		switch(strtolower($productType))
		{
			case 'tour':
				$reqData = [
					'product_id' => 'required',
					'scheduledate' => 'required',
					'quantity' => 'required'
				];
				$resMsg = [
					'product_id.required' => 'Paket produk yang dipilih tidak tersedia',
					'scheduledate.required' => 'Pilih tanggal perjalanan!',
					'quantity.required' => 'Tentukan jumlah paket/quantity yang akan dibeli.'
				];
				break;
			case 'souvenir':
				$reqData = [
					'product_id' => 'required',
					'quantity' => 'required'
				];
				$resMsg = [
					'product_id.required' => 'Paket produk yang dipilih tidak tersedia',
					'quantity.required' => 'Tentukan jumlah paket/quantity yang akan dibeli.'
				];
				break;
			case 'kuliner':
				$reqData = [
					'product_id' => 'required',
					'date_booking' => 'required',
					'time_booking' => 'required',
					'quantity' => 'required'
				];
				$resMsg = [
					'product_id.required' => 'Paket produk yang dipilih tidak tersedia',
					'date_booking.required' => 'Pilih tanggal reservasi!',
					'time_booking.required' => 'Pilih jam reservasi!',
					'quantity.required' => 'Tentukan jumlah pax.'
				];
				break;
			case 'transportasi':
				$reqData = [
					'product_id' => 'required',
					'start_date' => 'required',
					'end_date' => 'required'
				];
				$resMsg = [
					'product_id.required' => 'Produk yang dipilih tidak tersedia',
					'start_date.required' => 'Pilih tanggal awal reservasi',
					'end_date.required' => 'Pilih tanggal akhir reservasi'
				];
				break;
			default:
				$reqData = [];
				$resMsg = [];
		}
		return [$reqData, $resMsg];
	}

	private function buildCartDetail($productType, $request)
	{
		$data = false;
		switch(strtolower($productType))
		{
			case 'tour':
				$products = Product::join('tours', 'tours.product_id','products.product_id')
					->where('products.product_type_id', static::PRODUCT_TOUR)
					->where('products.product_id', $request->product_id)
					->first();		

				if($products)
				{
					$data = [
						'quantity' => intval($request->quantity),
						'product_id' => $request->product_id,
						'partner_id' => $products->partner_id,
						'title' => $products->title,
						'subtitle' => $products->subtitle,
						'trip_type' => $products->trip_type,
						'availability_type' => $products->availability_type,
						'product_thumbnail' => $products->product_thumbnail
					];
					if($products->availability_type == 'SCHEDULED')
					{
						$TourSchedule = TourSchedule::where('tour_schedule_id', $request->scheduledate)->first();
						if($TourSchedule)
						{
							$data['tour_schedule_id'] = $TourSchedule->tour_schedule_id;
							$data['schedule_date'] = $this->_func->scheduleStringDate($TourSchedule->schedule_date);
							$data['product_price'] = $TourSchedule->public_price;
							$data['partner_price'] = $TourSchedule->partner_price;
						}
					} else {
						$TourSchedule = TourSchedule::where('tour_id', $products->tour_id)
							->where('schedule_date', $request->scheduledate)
							->first();

						if($TourSchedule)
						{
							$data['tour_schedule_id'] = $TourSchedule->tour_schedule_id;
							$data['schedule_date'] = $this->_func->scheduleStringDate($request->scheduledate);
							$data['product_price'] = $TourSchedule->public_price;
							$data['partner_price'] = $TourSchedule->partner_price;
						}
					}
				}
				break;
			case 'souvenir':
				$products = Product::join('souvenirs', 'souvenirs.product_id','products.product_id')
					->join('partners','partners.partner_id','products.partner_id')
					->select([
						'products.product_id',
						'souvenirs.default_publish_price',
						'products.partner_id',
						'partners.province_id',
						'partners.city_id',
						'partners.subdistrict_id',
						'products.title',
						'products.subtitle',
						'products.product_thumbnail',
						'souvenirs.weight'
					])
					->where('products.product_type_id', static::PRODUCT_SOUVENIR)
					->where('products.product_id', $request->product_id)
					->first();		

				if($products)
				{
					$data = [
						'quantity' => intval($request->quantity),
						'product_id' => $request->product_id,
						'product_price' => $products->default_publish_price,
						'partner_price' => $products->default_partner_price,
						'partner_id' => $products->partner_id,
						'partner_province_id' => $products->province_id,
						'partner_city_id' => $products->city_id,
						'partner_subdistrict_id' => $products->subdistrict_id,
						'title' => $products->title,
						'subtitle' => $products->subtitle,
						'product_thumbnail' => $products->product_thumbnail,
						'weight' => $products->weight
					];
				}
				break;
			case 'kuliner':
				$products = Product::find($request->product_id);		

				if($products)
				{
					$data = [
						'schedule_date' => $this->_func->scheduleStringDate($request->date_booking) . ', ' . $request->time_booking . ' WIB',
						'quantity' => intval($request->quantity),
						'product_id' => $request->product_id,
						'partner_id' => $products->partner_id,
						'title' => $products->title,
						'product_thumbnail' => $products->product_thumbnail
					];
				}
				break;
			case 'transportasi':
				$products = Product::find($request->product_id);

				if($request->start_date == $request->end_date){
					$totDays = 1;
				} else {
					$datetime1 = new DateTime($request->start_date);
					$datetime2 = new DateTime($request->end_date);
					$interval = $datetime1->diff($datetime2);
					$totDays = $interval->format('%a') + 1;
				}
		
				if($products)
				{
					$totPublicPrice = RentCarSchedule::where('rent_car_id',$products->rentcar->rent_car_id)
														->whereDate('schedule_date','>=',new DateTime($request->start_date))
														->whereDate('schedule_date','<=',new DateTime($request->end_date))
														->sum('public_price');
		
					$data = [
						'product_id' => $products->product_id,
						'partner_id' => $products->partner_id,
						'product_thumbnail' => $products->rentcar->carmodel->car_image,
						'title' => $products->rentcar->carbrand->name . ' ' . $products->rentcar->carmodel->name,
						'subtitle' => ($request->driver == 1) ? 'Max ' . $products->rentcar->carmodel->passenger_capacity . ' Penumpang<br>Dengan Supir' : 'Max ' . $products->rentcar->carmodel->passenger_capacity . ' Penumpang<br>Tanpa Supir',
						'passenger' => $products->rentcar->carmodel->passenger_capacity,
						'start_date' => $this->_func->scheduleStringDate($request->start_date),
						'end_date' => $this->_func->scheduleStringDate($request->end_date),
						'driver' => ($request->driver == 1) ? 'YA' : 'TIDAK',
						'price' => $totPublicPrice/intval($totDays),
						'days' => intval($totDays),
						'quantity' => intval($request->quantity),
						'tot_price' => $totPublicPrice * intval($request->quantity)
					];
				}
				break;
			case 'akomodasi':
				$partner = Partner::where('partner_id',$request->partner_id)->first();
				if($partner)
				{
					$rooms = [];
					$date1 = $request->date1;
					$date2 = date('Y-m-d',strtotime('-1 day', strtotime($request->date2)));
					$productIds = array_column($request->rooms, 'productid');
					$products = Product::join('hotels', 'hotels.product_id', 'products.product_id')
						->join('hotel_schedules', 'hotel_schedules.hotel_id', 'hotels.hotel_id')
						->select([
							'products.product_id',
							'products.title',
							DB::raw('IFNULL(is_breakfast, 0) AS is_breakfast'),
							DB::raw('IFNULL(is_reschedule, 0) AS is_reschedule'),
							DB::raw('IFNULL(is_refund, 0) AS is_refund'),
							'hotel_schedules.schedule_date',
							'hotel_schedules.partner_price AS partner_price',
							'hotel_schedules.public_price AS product_price'
						])
						->whereBetween('hotel_schedules.schedule_date', [$date1, $date2])
						->whereIn('products.product_id', $productIds)
						->get();
							
					foreach($products as $prod)
					{	
						$arrId = array_search($prod->product_id, array_column($request->rooms, 'productid'));
						$quantity = $request->rooms[$arrId]['quantity'];
						$prod['quantity'] = (int)$quantity;
					}
					
					$data = [
						'product_id' => $request->partner_id,
						'product_type_id' => 4,
						'company_name' => $partner->company_name,
						'slug' => $partner->slug,
						'partner_thumbnail' => $partner->company_thumbnail,
						'checkin' => $request->date1,
						'checkout' => $request->date2,
						'days' => $this->_func->nightOfDays($date1, $date2),
						'rooms' => $products->toArray()
					];
				}
				break;
			default:
				
		}
		return $data;
	}

	public function ajaxAddToCart($productType, Request $request)
	{

		// check request validation 
		list($reqData, $resMsg) = $this->validatorRule($productType);

		$validator = \Validator::make($request->all(),$reqData,$resMsg);

		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		// return if product type is unknown
		if(!in_array($productType, static::productType)) 
		{
			return $this->_func->resJSON(200,0,[], 'Tipe produk tidak dikenali');
		}


		$details = $this->buildCartDetail($productType, $request);
		
		// return if product is not found
		if(!$details)
		{
			return $this->_func->resJSON(200,0,[], 'Produk yang dipilih tidak tersedia');
		}

		$response = $this->addCart($request->all(), $details);
		if($response)
		{
			return $this->_func->resJSON(200,1,$response);	
		}
		return $this->_func->resJSON(200,0);
	}

	public function ajaxAddShipmentToCart($productType, Request $request)
	{
		// check request validation 
		$reqData = [
			'name' => 'required|min:3',
			'phone' => 'required|min:5',
			'address' => 'required|min:10',
			'courier' => 'required',
			'courier_service' => 'required',
			'courier_cost' => 'required',
			'province_id' => 'required',
			'city_id' => 'required'
		];
		$resMsg = [
			'name.required' => 'Nama penerima wajib diisi',
			'name.min' => 'Nama minimal 3 huruf',
			'phone.required' => 'Nomor telepon penerima wajib diisi',
			'phone.min' => 'Nomor telepon minimal 5 digit',
			'address.required' => 'Alamat tujuan pengiriman wajib diisi',
			'courier.required'=>'Silahkan pilih pengiriman',
			'courier_service.required'=>'Silahkan pilih pengiriman',
			'courier_cost.required'=>'Silahkan pilih pengiriman',
			'province_id.required'=>'Propinsi tujuan pengiriman wajih diisi',
			'city_id.required'=>'Kota/Kabupaten tujuan pengiriman wajib diisi',
		];

		$validator = \Validator::make($request->all(),$reqData,$resMsg);
	
		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		$sessionCart = $this->sessionCart();
		$datetime = date('Y-m-d H:i:s');
		$carts = Cart::where('cart_key',$sessionCart)
			->where('product_type', 'souvenir')
			->first();

		if($carts)
		{
			$cartParam = unserialize($carts['cart_param']);

			$cartParam['shipment_to'] = [
				'name' => $request->name,
				'phone' => $request->phone,
				'address' => $request->address,
				'province_id' => $request->province_id,
				'city_id' => $request->city_id,
				'subdistrict_id' => $request->subdistrict_id
			];
			$cartParam['shipment_courier'] = [
				'courier' => $request->courier,
				'courier_service' => $request->courier_service,
				'courier_cost' => $request->courier_cost
			];
			$cartParam = serialize($cartParam);
			Cart::where('cart_key',$sessionCart)
				->where('product_type', 'souvenir')
				->update(['cart_param'=>$cartParam]);

			return $this->_func->resJSON(200,1,$sessionCart);
		}
		return $this->_func->resJSON(200,0);
	}

	public function ajaxAddPickupToCart($productType, Request $request)
	{
		// check request validation 
		$reqData = [
			'pickuplocation' => 'required',
			'pickuptime' => 'required',
			'returnlocation' => 'required',
			'returntime' => 'required'
		];
		$resMsg = [
			'pickuplocation.required' => 'Lokasi Penjemputan wajib diisi',
			'pickuptime.required' => 'Waktu Penjemputan wajib diisi',
			'returnlocation.required' => 'Lokasi Pengembalian wajib diisi',
			'returntime.required' => 'Waktu Pengembalian wajib diisi'
		];

		$validator = \Validator::make($request->all(),$reqData,$resMsg);
	
		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		$sessionCart = $this->sessionCart();
		$datetime = date('Y-m-d H:i:s');
		$carts = Cart::where('cart_key',$sessionCart)
			->where('product_type', 'transportasi')
			->first();

		if($carts)
		{
			$cartParam = unserialize($carts['cart_param']);

			$cartParam['schedule_date'] = $cartParam['start_date'] . ' ' . $request->pickuptime . ' WIB - ' . $cartParam['end_date'] . ' ' . $request->returntime . ' WIB';
			$cartParam['pickup'] = [
				'pickup_location' => $request->pickuplocation,
				'pickup_time' => $request->pickuptime . ' WIB',
				'return_location' => $request->returnlocation,
				'return_time' => $request->returntime . ' WIB',
				'spesial_req' => $request->spesialreq
			];
			$cartParam = serialize($cartParam);
			Cart::where('cart_key',$sessionCart)
				->where('product_type', 'transportasi')
				->update(['cart_param'=>$cartParam]);

			return $this->_func->resJSON(200,1,$sessionCart);
		}
		return $this->_func->resJSON(200,0);
	}

	public function ajaxSessionOrder(Request $request)
	{

		return [
			'status' => 'success',
			'session' => rand()
		];
	}
}