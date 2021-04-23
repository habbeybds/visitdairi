<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\MidtransRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Partner;
use App\Models\Cart;
use App\Models\PayChannel;
use App\Models\Product;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\Trans;
use App\Models\TransDetail;
use App\Models\TransPayment;
use App\Models\TransPaymentRequest;
use App\Models\TransNotify;
use App\Models\Province;
use App\Models\City;
use App\Models\SubDistrict;
use DB;
use Log;
use Mail;

class PaymentController extends BaseController
{

	protected $_config;
	protected $_func;

	const bank = [
		'alfamart' => 'Alfamart'
	];

	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}

	protected function getOrderId()
	{

	}

	public function paymentMethod($orderCode)
	{
		$config = $this->_config->get();
		$data['config'] = $config;

		$trans = Trans::where('trans_code', $orderCode)->first();
		if(!$trans)
		{
			return 'TRANSACTION NOT FOUND';
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();
		if(!$transDetail)
		{
			return 'TRANSACTION NOT FOUND';
		}

		// get productDetail
		$productDetail = unserialize($transDetail->product_detail);

		// init shipmentFee
		$shipmentFee = 0;
		if($transDetail->product_type_id == '2')
		{
			if(isset($productDetail['shipment_courier']))
			{
				$data['courier'] = $productDetail['shipment_courier'];
			}
			$shipmentFee = (int)$productDetail['shipment_courier']['courier_cost'];
		}
		elseif($transDetail->product_type_id == '4')
		{
			$productDetail['title'] = $productDetail['company_name'];
			$productDetail['subtitle'] = '';
			$productDetail['product_thumbnail'] = $productDetail['partner_thumbnail'];
		}

		//return $productDetail;
		$totalProductPrice = (int)$trans->total_product_price + $shipmentFee;

		// load payment channel
		$payChannels = PayChannel::where('published', 1)
			->select([
				'pay_channel_id AS id',
				'name',
				'link_rewrite AS code',
				'logo_url AS logo',
				'pay_fee_amount AS fee',
				'pay_fee_percentage AS fee_percent',
				'description',
				'timelimit'
			])
			->orderBy('seq', 'ASC')
			->get();

		$tabNav = '';
		$tabContent = '';
		if($payChannels)
		{
			$active = true;
			foreach($payChannels as $channel)
			{
				$activeClass = '';
				if($active)
				{
					$activeClass = 'active';
					$paymentFee	= $channel->fee + ($totalProductPrice * $channel->fee_percent / 100);
					$totalAmount = $totalProductPrice + $paymentFee;
				}
				$percentFee = $totalProductPrice * $channel->fee_percent / 100;
				$tabNav .= '<li><a href="#pay-'.$channel->id.'" class="'.$activeClass.'" data-toggle="tab" data-fee="'.$channel->fee.'" data-fee-percent="'.$percentFee.'" onclick="">'.$channel->name.'</a></li>';
				$tabContent .= '<div class="tab-pane '.$activeClass.'" id="pay-'.$channel->id.'">'.
                    '<div class="logo-pay">'.
                    '    <img src="'.config('constants.UPLOAD_PATH').$channel->logo.'" alt="img" style="height:100px;">'.
                    '</div>'.
                    '<div class="wrap-content">'.
                    '    '.$channel->description.''.
                    '    <div class="terms">'.
                    '        <div class="custom-control custom-checkbox">'.
                    '            <input type="checkbox" class="custom-control-input chk-tnc" data-channel="'.$channel->id.'" id="customControlAutosizing'.$channel->id.'">'.
                    '            <label class="custom-control-label" for="customControlAutosizing'.$channel->id.'">'.
                    '                <p>Dengan mengklik tombol di bawah, Anda menyetujui <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a></p>'.
                    '            </label>'.
                    '        </div>'.
                    '    </div>'.
                    '    <div class="wrap-btn">'.
                    '        <button data-channel="'.$channel->id.'" class="btn btn-primary btn-block btn-payment ch'.$channel->id.'" disabled>Proses Pembayaran</button>'.
                    '    </div>'.
                    '</div>'.
                '</div>';
				$active = false;
			}
		}

		$data['trans'] = $trans;
		$data['transDetail'] = $transDetail;

		$data['tabNav'] = $tabNav;
		$data['tabContent'] = $tabContent;
		$data['productDetail'] = $productDetail;
		$data['paymentFee'] = $paymentFee;
		$data['totalProductPrice'] = $totalProductPrice;
		$data['totalAmount'] = $totalAmount;
		$data['auth'] = new CustomerRepository;

		return view('order.payment-method', $data);
	}

	public function ajaxGetCharge(Request $request)
	{
		$tokenId = $request->post('token_id');
		$orderId = $request->post('order_id');
		$paymentChannelId = $request->post('channel');

		// get gross amount
		$trans = Trans::where('trans_id', $orderId)->first();
		if(!$trans)
		{
			return [
				'status' => 'failed',
				'message' => 'Transaksi tidak ditemukan.'
			];
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();
		if(!$transDetail)
		{
			return [
				'status' => 'failed',
				'message' => 'Transaksi tidak ditemukan.'
			];
		}

		// get paychannl fee
		$channel = PayChannel::where('pay_channel_id', $paymentChannelId)->where('published', 1)->first();
		if(!$channel) {
			return [
				'status' => 'failed',
				'message' => 'Channel pembayaran tidak ditemukan.'
			];
		}

		$shipmentFee = 0;
		if($transDetail->product_type_id == '2')
		{
			$productDetail = unserialize($transDetail['product_detail']);
			if(isset($productDetail['shipment_courier']))
			{
				$data['courier'] = $productDetail['shipment_courier'];
			}
			$shipmentFee = (int)$productDetail['shipment_courier']['courier_cost'];
		}

		$totalProductPrice = (int)$trans->total_product_price + $shipmentFee;
		$paymentFee = $channel->pay_fee_amount + ($totalProductPrice * $channel->pay_fee_percentage / 100);
		$grossAmount = round($totalProductPrice + $paymentFee);

		Trans::where('trans_id', $orderId)->update([
			'payment_fee' => $paymentFee,
			'total_payment' => $grossAmount,
			'timelimit' => date("Y-m-d H:i:s", strtotime("+" . ($channel->timelimit) . " minutes"))
		]);

		$customer_details = array(
			'first_name'    =>  $trans->customer_name, //optional
			'last_name'     =>  '', //optional
			'email'         =>  $trans->customer_email, //mandatory
			'phone'         =>  $trans->customer_phone //mandatory
		);

		$expiry = array(
			'unit'     		=>  'minutes', 
			'duration'		=>  $channel->timelimit
		);

		// Write Payment
		$dataPayment = [
			'trans_id' => $orderId,
			'pay_channel_id' => $paymentChannelId,
			'total_transaction' => $trans->total_payment,
			'payment_fee' => $paymentFee,
			'total_payment' => $grossAmount,
			'card_number' => '',
			'va_number' => '',
			'bank' => '',
			'payment_link' => '',
			'status' => 1,
			'created_at' => date('Y-m-d H:i:s'),
			'modified_at' => date('Y-m-d H:i:s'),
		];
		$transPaymentId = TransPayment::insertGetId($dataPayment);

		// get snap token
		$midtrans = new MidtransRepository;
		if($channel->link_rewrite == 'credit-card'){
			$midtrans->setOrderId($trans->trans_code)
						->setTransPaymentId($transPaymentId)
						->setChannel($channel->link_rewrite)
						->setGrossAmount($grossAmount)
						->setCustomerDetails($customer_details)
						->setExpiry('');
		} else {
			$midtrans->setOrderId($trans->trans_code)
						->setTransPaymentId($transPaymentId)
						->setChannel($channel->link_rewrite)
						->setGrossAmount($grossAmount)
						->setCustomerDetails($customer_details)
						->setExpiry($expiry);
		}
		
		$result = $midtrans->getSnapToken();
		if($result && gettype($result) == 'string')
		{
			$paymentLink = config('constants.MIDTRANS.URL') . $result . '#/' . $channel->link_rewrite;
			
			if(!$transPaymentId)
			{
				return [
					'status' => 'failed',
					'message' => 'Proses pembayaran tidak berhasil.'
				];
			}

			return [
				'status' => 'success',
				'redirect' => $paymentLink
			];
		}
		return [];
	}

	protected function doIssued($transId, $paymentDate, $transactionId)
	{
		// check availability
		// $transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();
		
		// product tour
		// if($transDetail->product_type_id == 1)
		// {
			// update transaction
			Trans::where('trans_id', $transId)->update([
				'payment_date' => $paymentDate,
				'status' => 2
			]);
			TransPayment::where('trans_id', $transId)->update([
				'transaction_id' => $transactionId,
				'status' => 2
			]);

			// end transaction
		// }
		// send email
	}

	public function notification(Request $request, MidtransRepository $midtrans)
	{
		// logging
        $datetime = date('Y-m-d H:i:s');

        $va = '';
        $bank = '';
        $verificationResult = '';

        $orderId = $request->order_id;
        $signature = $request->signature_key;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $paymentType = $request->payment_type;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

		$input = $request->all();
		
        // get transaction
		$trans = Trans::join('trans_det', 'trans_det.trans_id', 'trans.trans_id')
			->join('trans_payment', 'trans_payment.trans_id', 'trans.trans_id')
			->join('pay_channels', 'pay_channels.pay_channel_id', 'trans_payment.pay_channel_id')
			->join('product_types', 'product_types.product_type_id', 'trans_det.product_type_id')
			// ->leftJoin('products', 'products.product_id', 'trans_det.product_id')
			// ->join('partners', 'partners.partner_id', 'products.partner_id')
			// ->join('subdistricts', 'partners.subdistrict_id', 'subdistricts.subdistrict_id')
			// ->join('cities', 'partners.city_id', 'cities.city_id')
			// ->join('provinces', 'partners.province_id', 'provinces.province_id')
			->where('trans.trans_code', $orderId)
			->select([
				'trans.*',
				'trans_det.product_id',
				'trans_det.product_detail',
				'trans_payment.trans_payment_id',
				'pay_channels.pay_channel_id',
				'pay_channels.name AS channel_name',
				'product_types.slug AS product_type'
				// 'products.product_id',
				// 'products.title',
				// 'products.subtitle',
				// 'partners.email AS partner_email',
				// 'partners.company_name',
				// 'partners.fullname AS partner_name',
				// 'partners.address AS address',
				// 'subdistricts.subdistrict_name',
				// 'cities.type AS city_type',
				// 'cities.city_name',
				// 'provinces.name AS province_name'
			])
			->first();

		$transId = $trans->trans_id;

		// Insert to notify
		$dataNotify = [
			'trans_payment_id' => $trans->trans_payment_id,
			'param_notify' => is_string($input) ? $input : json_encode($input),
			'verification_result' => '',
			'created_at' => date('Y-m-d H:i:s'),
			'modified_at' => date('Y-m-d H:i:s')
		];
	
		//return $trans;
		$isValid = $midtrans->validSignature($signature, $orderId, $statusCode, $grossAmount);
		$transtime = strtotime($request->transaction_time);
		$timelimit = strtotime($trans->timelimit);

		if(!$isValid) {
			$verificationResult = 'Invalid signature';
		} elseif(!$trans) {
			$verificationResult = 'Transaksi tidak ditemukan';
		} elseif(floatval($grossAmount) < floatval($trans->total_payment)) {
			$verificationResult = 'Pembayaran tidak sesuai / kurang bayar.';
		} elseif(($trans->pay_channel_id != 1)&&($timelimit < $transtime)) {
			$verificationResult = 'Expired Timelimit';
		} else {
			if($statusCode == '201') {

				$verificationResult = 'Pending transaction';
				if($paymentType == 'bank_transfer') {
					if(!empty($request->input('permata_va_number'))){
                        $va = $request->input('permata_va_number');
                        $bank = 'Permata';
                    } else {
                        $data = $request->input('va_numbers');
                        foreach ($data as $row){
                            $va = $row['va_number'];
                            $bank = $row['bank'];
                        }
                    }
				} elseif($paymentType == 'cstore') {
					$va = $request->payment_code;
                    $bank = $request->store;
				} elseif($paymentType == 'echannel'){
                    $va = '(' . $request->biller_code . ') ' . $request->bill_key;
                    $bank = 'Mandiri';
				}
				
                TransPayment::where('trans_id',$transId)->update([
                	'va_number' => $va,
                	'bank' => $bank,
                	'transaction_id' => $request->transaction_id
				]);
				
				$productDetail = unserialize($trans->product_detail);
				$partner = Partner::find($productDetail['partner_id']);

				// send email to customer
				$params = [
					'subject'=>'Menunggu Pembayaran',
					'template'=>'waiting-payment',
					
					'customerName' => $trans->customer_name,
					'transCode' => $trans->trans_code,
					'productTitle' => $productDetail['title'] . ' - ' . $productDetail['subtitle'],
					'scheduleDate' => $productDetail['schedule_date'],
					
					'paymentMethod' => $trans->channel_name,
					'vaNumber' => $va,
                	'bank' => $bank,
					'totalPayment' => number_format($trans->total_payment,0,",","."),
                	'timelimit' => date("d M Y H:i:s", strtotime($trans->timelimit))
				];
				@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

			} else if($fraudStatus != 'accept'){                                                   // fraud transaction
                $verificationResult = 'fraud transaction';
            } else if(($transactionStatus != 'settlement')&&($transactionStatus != 'capture')){   // 
                $verificationResult = 'not valid transaction status';
            } elseif(($statusCode == '200') && ($fraudStatus == 'accept') && in_array($transactionStatus, ['settlement','capture'])) {

				if($trans->status == 1) {
					$verificationResult = 'success';
					$this->doIssued($transId, $request->transaction_time, $request->transaction_id);

					if($trans->product_type == 'tour'){

						$productDetail = unserialize($trans->product_detail);
						$partner = Partner::find($productDetail['partner_id']);

						// send email to customer
						$params = [
							'subject'=>'Pembayaran Sukses',
							'template'=>'receipt',
							
							'transCode' => $trans->trans_code,
							'productTitle' => $productDetail['title'],
							'scheduleDate' => $productDetail['quantity'],
							'companyName' => $partner->company_name,
							
							'customerName' => $trans->customer_name,
							'customerPhone' => $trans->customer_phone,
							'customerEmail' => $trans->customer_email,

							'invoiceNumber' => $trans->invoice_number,
							'paymentMethod' => $trans->channel_name,
							'paymentDate' => $this->_func->scheduleStringDate($request->transaction_time) . ' | ' . date('H:i', strtotime($request->transaction_time)) . ' WIB',
							'totalPayment' => number_format($trans->total_payment,0,",",".")
						];
						@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

						// send email to partner
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-partner';
						@Mail::to($partner->email)->send(new \App\Mail\Mailer($params));

						// // send email to admin
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-admin';
						@Mail::to(config('constants.ADMIN_EMAIL_NOTIF'))->send(new \App\Mail\Mailer($params));

					} else if($trans->product_type == 'souvenir'){
						
						$productDetail = unserialize($trans->product_detail);
						$partner = Partner::find($productDetail['partner_id']);

						$province = Province::find($productDetail['shipment_to']['province_id']);
                        $city = City::find($productDetail['shipment_to']['city_id']);
						$subdistrict = SubDistrict::find($productDetail['shipment_to']['subdistrict_id']);

						// send email to customer
						$params = [
							'subject'=>'Pembayaran Sukses',
							'template'=>'receipt-souvenir',
							
							'transCode' => $trans->trans_code,
							'productTitle' => $productDetail['title'],
							'quantity' => $productDetail['quantity'],
							'companyName' => $partner->company_name,
							
							'shipmentName' => $productDetail['shipment_to']['name'],
							'shipmentPhone' => $productDetail['shipment_to']['phone'],
							'shipmentAddress' => $productDetail['shipment_to']['address'] . '<br>Kecamatan ' . $subdistrict->subdistrict_name . '<br>' . $city->type . ' ' . $city->city_name . '<br>Provinsi ' . $province->name,
							'shipmentCourier' => strtoupper($productDetail['shipment_courier']['courier']),
							'shipmentCourierService' => strtoupper($productDetail['shipment_courier']['courier_service']),
							
							'customerName' => $trans->customer_name,
							'customerPhone' => $trans->customer_phone,
							'customerEmail' => $trans->customer_email,

							'invoiceNumber' => $trans->invoice_number,
							'paymentMethod' => $trans->channel_name,
							'paymentDate' => $this->_func->scheduleStringDate($request->transaction_time) . ' | ' . date('H:i', strtotime($request->transaction_time)) . ' WIB',
							'totalPayment' => number_format($trans->total_payment,0,",",".")
						];
						@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

						// send email to partner
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-partner-souvenir';
						@Mail::to($partner->email)->send(new \App\Mail\Mailer($params));

						// // send email to admin
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-admin-souvenir';
						@Mail::to(config('constants.ADMIN_EMAIL_NOTIF'))->send(new \App\Mail\Mailer($params));

					} else if($trans->product_type == 'akomodasi'){

						$partner = Partner::find($trans->product_id);

						$productDetail = unserialize($trans->product_detail);
						$roomDetail = '';
						foreach($productDetail['rooms'] as $row){
							$breakfast = ($row['is_breakfast'] == 0) ? 'tanpa sarapan' : 'dengan sarapan';
							$roomDetail .= '<tr>' .
												'<td class="" width="75%" valign="top" align="left" style="padding-left: 15px;padding-top:10px;padding-bottom:10px;font-size: 14px;line-height:24px;font-family: Poppins, sans-serif;color: #30373b;border-bottom: 1px dashed #D5D5D5;">' .
													'<span>' . $row['title'] . '<br><span style="font-size: 12px;">' . $breakfast . '</span></span>' .
												'</td>' .
												'<td class="" width="25%" valign="top" align="left" style="padding-left: 15px;padding-top:10px;padding-bottom:10px;font-size: 14px;line-height:24px;font-family: Poppins, sans-serif;color: #30373b;border-bottom: 1px dashed #D5D5D5;">' .
													'<span>' . $row['quantity'] . '</span>' .
												'</td>' .
											'</tr>';
						}
						
						$province = Province::find($partner->province_id);
						$provinceName = ($province) ? $province->name : '';

						$city = City::find($partner->city_id);
						$cityName = ($city) ? $city->type . ' ' : '';
						$cityName .= ($city) ? $city->city_name : '';
						
						$subdistrict = SubDistrict::find($partner->subdistrict_id);
						$subdistrictName = ($subdistrict) ? $subdistrict->subdistrict_name : '';

						// send email to customer
						$params = [
							'subject'=>'Pembayaran Sukses',
							'template'=>'receipt-akomodasi',
							
							'transCode' => $trans->trans_code,
							'companyName' => $productDetail['company_name'],
							'address' => $partner->address . '<br>Kecamatan ' . $subdistrictName . '<br>' . $cityName . '<br>Provinsi ' . $provinceName,
							'checkin' => date("d M Y", strtotime($productDetail['checkin'])),
							'checkout' => date("d M Y", strtotime($productDetail['checkout'])),

							'roomDetail' => $roomDetail,

							'customerName' => $trans->customer_name,
							'customerPhone' => $trans->customer_phone,
							'customerEmail' => $trans->customer_email,

							'invoiceNumber' => $trans->invoice_number,
							'paymentMethod' => $trans->channel_name,
							'paymentDate' => $this->_func->scheduleStringDate($request->transaction_time) . ' | ' . date('H:i', strtotime($request->transaction_time)) . ' WIB',
							'totalPayment' => number_format($trans->total_payment,0,",",".")
						];
						@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

						// send email to partner
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-partner-akomodasi';
						@Mail::to($partner->email)->send(new \App\Mail\Mailer($params));

						// // send email to admin
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-admin-akomodasi';
						@Mail::to(config('constants.ADMIN_EMAIL_NOTIF'))->send(new \App\Mail\Mailer($params));

					} else if($trans->product_type == 'transportasi'){
						
						$productDetail = unserialize($trans->product_detail);
						$partner = Partner::find($productDetail['partner_id']);

						// send email to customer
						$params = [
							'subject'=>'Pembayaran Sukses',
							'template'=>'receipt-transportasi',
							
							'productTitle' => $productDetail['title'],
							'scheduleDate' => $productDetail['start_date'] . ' - ' . $productDetail['end_date'],
							'quantity' => $productDetail['quantity'],
							'driver' => $productDetail['driver'],
							'companyName' => $partner->company_name,

							'pickup' => $productDetail['pickup']['pickup_location'] . ' ' . $productDetail['pickup']['pickup_time'],
							'return' => $productDetail['pickup']['return_location'] . ' ' . $productDetail['pickup']['return_time'],
							'spesialReq' => $productDetail['pickup']['spesial_req'],
							
							'customerName' => $trans->customer_name,
							'customerPhone' => $trans->customer_phone,
							'customerEmail' => $trans->customer_email,

							'invoiceNumber' => $trans->invoice_number,
							'paymentMethod' => $trans->channel_name,
							'paymentDate' => $this->_func->scheduleStringDate($request->transaction_time) . ' | ' . date('H:i', strtotime($request->transaction_time)) . ' WIB',
							'totalPayment' => number_format($trans->total_payment,0,",",".")
						];
						@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

						// send email to partner
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-partner-transportasi';
						@Mail::to($partner->email)->send(new \App\Mail\Mailer($params));

						// // send email to admin
						$params['subject'] = 'Pesanan baru menunggu';
						$params['template'] = 'neworder-admin-transportasi';
						@Mail::to(config('constants.ADMIN_EMAIL_NOTIF'))->send(new \App\Mail\Mailer($params));

					}
					
				} elseif($trans->status == 2) {
					$verificationResult = 'Double Payment';
				} else {

				}
			}
		}

		$dataNotify['verification_result'] = $verificationResult;

		TransNotify::insert($dataNotify);

		return $verificationResult;

	}

	public function finish(Request $request)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$transCode = $request->order_id;
		$statusCode = $request->status_code;
		$transactionStatus = $request->transaction_status;

		$trans = Trans::where('trans_code', $transCode)->first();
		if(!$trans)
		{
			return 'TRANSAKSI TIDAK DITEMUKAN';
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();

		$payment = TransPayment::join('pay_channels', 'pay_channels.pay_channel_id', 'trans_payment.pay_channel_id')
			->where('trans_id', $trans->trans_id)
			->select([
				'trans_payment.*',
				'pay_channels.name AS payment_channel_name',
				'pay_channels.link_rewrite AS payment_channel_code',
				'pay_channels.logo_url AS payment_channel_logo'
			])
			->first();

		if(!$payment)
		{
			return 'TRANSAKSI PEMBAYARAN TIDAK DITEMUKAN';
		}

		if($statusCode == '200') {
			$data['trans'] = $trans;
			$data['transDetail'] = unserialize($transDetail->product_detail);
			$data['payment'] = $payment;
			$data['date'] = false;
			if($transDetail->product_type_id == 1) {
				$data['date'] = $this->_func->scheduleStringDate($data['transDetail']['schedule_date']);
			}
			elseif($transDetail->product_type_id == 4) {
				$data['transDetail']['title'] = $data['transDetail']['company_name'];
				$data['transDetail']['subtitle'] = '';
				$data['transDetail']['product_thumbnail'] = $data['transDetail']['partner_thumbnail'];
			}
			$data['payMethod'] = $payment->payment_channel_name;

			return view('payment.finish', $data);
		} elseif($statusCode == '201') {
			if($transactionStatus == 'pending')
			{
				$timelimit = $trans->timelimit;
				$data['timelimit'] = $this->_func->scheduleStringDate($timelimit) . ' ' . date('H:i', strtotime($timelimit));
				$data['trans'] = $trans;
				$data['transDetail'] = unserialize($transDetail->product_detail);
				$data['payment'] = $payment;
				$data['date'] = false;
				if($transDetail->product_type_id == 1) {
					$data['date'] = $data['transDetail']['schedule_date'];
				} 
				elseif($transDetail->product_type_id == 4) {
					$data['transDetail']['title'] = $data['transDetail']['company_name'];
					$data['transDetail']['subtitle'] = '';
					$data['transDetail']['product_thumbnail'] = $data['transDetail']['partner_thumbnail'];
				}
				$data['payMethod'] = $payment->payment_channel_name;
				return view('payment.waiting', $data); 
			}
		} elseif($statusCode == '202') {

		} else {

		}
		
		return view('payment.finish', $data);
	}

	public function unfinish(Request $request)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$transCode = $request->order_id;
		$statusCode = $request->status_code;
		$transactionStatus = $request->transaction_status;

		$trans = Trans::where('trans_code', $transCode)->first();
		if(!$trans)
		{
			return 'TRANSAKSI TIDAK DITEMUKAN';
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();

		$payment = TransPayment::join('pay_channels', 'pay_channels.pay_channel_id', 'trans_payment.pay_channel_id')
			->where('trans_id', $trans->trans_id)
			->select([
				'trans_payment.*',
				'pay_channels.name AS payment_channel_name',
				'pay_channels.link_rewrite AS payment_channel_code',
				'pay_channels.logo_url AS payment_channel_logo'
			])
			->first();

		if(!$payment)
		{
			return 'TRANSAKSI PEMBAYARAN TIDAK DITEMUKAN';
		}

		$data['trans'] = $trans;
		$data['transDetail'] = unserialize($transDetail->product_detail);
		//return $trans;
		//return $data['transDetail'];
		$data['payment'] = $payment;
		$data['date'] = false;
		if($transDetail->product_type_id == 1) {
			$data['date'] = $this->_func->scheduleStringDate($data['transDetail']['schedule_date']);
		} 
		$data['bankChannel'] = isset(static::bank[$payment->bank]) ? static::bank[$payment->bank] : '';

		return view('payment.unfinish', $data);
	}

	public function error(Request $request)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$transCode = $request->order_id;
		$statusCode = $request->status_code;
		$transactionStatus = $request->transaction_status;

		$trans = Trans::where('trans_code', $transCode)->first();
		if(!$trans)
		{
			return 'TRANSAKSI TIDAK DITEMUKAN';
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();

		$payment = TransPayment::join('pay_channels', 'pay_channels.pay_channel_id', 'trans_payment.pay_channel_id')
			->where('trans_id', $trans->trans_id)
			->select([
				'trans_payment.*',
				'pay_channels.name AS payment_channel_name',
				'pay_channels.link_rewrite AS payment_channel_code',
				'pay_channels.logo_url AS payment_channel_logo'
			])
			->first();

		if(!$payment)
		{
			return 'TRANSAKSI PEMBAYARAN TIDAK DITEMUKAN';
		}

		$data['trans'] = $trans;
		$data['transDetail'] = unserialize($transDetail->product_detail);
		//return $trans;
		//return $data['transDetail'];
		$data['payment'] = $payment;
		$data['date'] = false;
		if($transDetail->product_type_id == 1) {
			$data['date'] = $this->_func->scheduleStringDate($data['transDetail']['schedule_date']);
		} 
		$data['bankChannel'] = isset(static::bank[$payment->bank]) ? static::bank[$payment->bank] : '';

		return view('payment.unfinish', $data);
	}
	
	public function confirmation($transCode)
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;

		$trans = Trans::where('trans_code', $transCode)->first();
		if(!$trans)
		{
			return 'TRANSAKSI TIDAK DITEMUKAN';
		}

		$transDetail = TransDetail::where('trans_id', $trans->trans_id)->first();

		$data['trans'] = $trans;
		$data['transDetail'] = unserialize($transDetail->product_detail);
				
		// send email to customer
		$params = [
			'subject'=>'Pemesanan',
			'template'=>'confirmation',
			'trans' => $trans,
			'transDetail' => unserialize($transDetail->product_detail)
		];
		@Mail::to($trans->customer_email)->send(new \App\Mail\Mailer($params));

		// send email to partner
		$params['subject'] = 'Pesanan baru menunggu';
		$params['template'] = 'confirmation-partner';
		@Mail::to($transDetail->product->partner->email)->send(new \App\Mail\Mailer($params));

		// // send email to admin
		$params['subject'] = 'Pesanan baru menunggu';
		$params['template'] = 'confirmation-admin';
		@Mail::to(config('constants.ADMIN_EMAIL_NOTIF'))->send(new \App\Mail\Mailer($params));

		return view('payment.confirmation', $data);
	}

}