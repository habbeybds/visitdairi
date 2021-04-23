<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\EmailRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Trans;
use App\Models\Review;
use Mail;
use DB;

class CustomerController extends BaseController
{

	protected $_config = null;
	protected $_func = null;
	protected $_cust = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions, CustomerRepository $customer)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
		$this->_cust = $customer;
	}

	public function login(Request $request)
	{
		// get config
		$config = $this->_config->get(); 
		$data['config'] = $config;
		$data['redirect'] = $request->get('redirect') ? $request->get('redirect') : route('home');

		return view('customer.login', $data);
	}

	public function register()
	{

		// get config
		$config = $this->_config->get(); 
		$data['config'] = $config;
		return view('customer.register', $data);
	}

	public function forgotPassword()
	{
		// get config
		$config = $this->_config->get(); 
		$data['config'] = $config;

		return view('customer.forgot-password', $data);
	}

	public function profile()
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$auth = new CustomerRepository;
		if($auth->logged()) 
		{
			$data['auth'] = $auth;
			$data['layout'] = 'profile';
			$data['detail'] = $auth->getCustomer(true);
			$data['provinces'] = Province::select(['province_id','name'])->orderBy('name', 'ASC')->get();
		//return $data['detail'];
			return view('customer.account', $data);
		}
		return redirect('customer/login');
	}

	public function profileUpdate(Request $request)
	{

		$dob = $request->post('dob');
		$matched = preg_match_all('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $dob, $matches);
		if(!$matched) 
		{
			return ['status'=>'error','message'=>'Silahkan masukkan tanggal lahir Anda!'];
		}

		array_shift($matches);
		$dob = $matches[2][0].'-'.$matches[1][0].'-'.$matches[0][0];

		$auth = new CustomerRepository;
		$customer = $auth->getCustomer(true);
		$customerId = $auth->getId();
		
		$success = $this->_cust->update([
			'salutation' => $request->salutation,
			'first_name' => $request->fname,
			'last_name' => $request->lname,
			'gender' => $request->gender,
			'dob' => $dob,
			'address' => $request->address,
			'postcode' => $request->kodepos,
			'province_id' => $request->province,
			'city_id' => $request->city,
			'subdistrict_id' => $request->subdistrict
		], $customerId);
		return $this->_func->resJSON(200, 1);
	}

	public function orderList()
	{
		$config = $this->_config->get();
		$data['config'] = $config;

		$auth  = new CustomerRepository;
		$data['auth'] = $auth;
		$data['layout'] = 'orderlist';

		if($auth->logged()) {
			$orders = [];
			$id = $auth->getId();
			$cust = $auth->getCustomer();
			$orderResult = Trans::join('trans_det', 'trans_det.trans_id','trans.trans_id')
				->leftjoin('reviews', 'reviews.content_id', 'trans.trans_id')
				->select([
					'trans.trans_id',
					'trans.invoice_number',
					'trans.invoice_date',
					'trans.trans_code',
					'trans_det.product_type_id',
					'trans_det.product_detail',
					'trans.timelimit',
					'trans.status',
					'trans.shipment_status',
					'trans.awb_number',
					'reviews.comments',
					'reviews.star_review',
					'trans.created_at'
				])
				// ->addSelect([
				// 	'enableComment' => Review::select([
				// 		DB::raw('IF(COUNT(review_id) == 0, 1, 0) AS enableComment')
				// 	])
				// 	->whereColumn('content_id','trans.trans_id')
				// 	->where('trans.status', 2)
				// 	->where('content_type','product')
				// 	->limit(1)
				// ])
				->where('customer_email', $cust['email'])
				->orWhere('trans.customer_id', $id)
				->orderBy('trans.trans_id', 'DESC')
				->paginate(5);
			
			$data['orderResult'] = $orderResult->toArray();
			//return $data['orderResult'];
			if(sizeof($orderResult) > 0)
			{
				foreach($orderResult as $order) {

					// serialize detail
					$details = unserialize($order['product_detail']);

					// if has expired
					$hasExpired = false;
					if(($order->status == 0 || $order->status == 1) 
						&& strtotime($order->timelimit) < time()
						&& $order->product_type_id != 3) {
						$hasExpired = true;
					}

					// submit comment allowed
					$enableComment = false;
					if($order->status == 2) {
						$enableComment = true;
					} 

					// enable tracking
					$enableTracking = false;
					$tracking_allowed = config('constants.RAJAONGKIR.AVAILABLE_WAYBILL');
					$courier = '';
					$awb = '';
	
					if($order->status == 2 
						&& $order->product_type_id == 2
						&& in_array($details['shipment_courier']['courier'], $tracking_allowed)
						&& !empty($order->awb_number)) {
							$enableTracking = true;
							$courier = $details['shipment_courier']['courier'];
							$awb = $order->awb_number;
					}
				
					$dataOrder = [
						'id' => $order->trans_id,
						'type_id' => $order->product_type_id,
						'code' => $order->trans_code,
						'invoice_number' => $order->invoice_number,
						'invoice_date' => $order->invoice_date,
						'status' => $order->status,
						'hasExpired' => $hasExpired,
						'enableComment' => $enableComment,
						'enableTracking' => $enableTracking,
						'comments' => $order->comments,
						'shipment_status' => $order->shipment_status,
						'courier' => $courier,
						'awb' => $awb,
						'rating' => $order->star_review,
						'timelimit' => $this->_func->scheduleStringDate($order->timelimit) . ' ' . date('H:i', strtotime($order->timelimit)),
						'orderDate' => $this->_func->dateIDFormat($order->created_at) . ' ' . date('H:i', strtotime($order->created_at)),
						'details' => $details
					];

					switch($order->product_type_id)
					{
						case '1':
							$dataOrder['product_type_name'] = 'Paket Wisata';
							break;
						case '2':
							$dataOrder['product_type_name'] = 'Souvenir';
							break;
						case '3':
							$dataOrder['product_type_name'] = 'Kuliner';
							break;
						case '4':
							$dataOrder['product_type_name'] = 'Akomodasi';
							$dataOrder['details']['title'] = 'Akomodasi';
							break;
						case '5':
							$dataOrder['product_type_name'] = 'Transportasi';
							break;
						default:
		
					}

					$orders[] = $dataOrder;
				}	
			}

			$data['orders'] = $orders;
			
			//return $orderResult;
			return view('customer.account', $data);
		}
		
		return redirect('customer/login');
	}

	public function ajaxSubmitReview(Request $request)
	{
		$reqData = [
			'trans_id' => 'required',
			'comment' => 'required',
			'star' => 'required'
		];
		$resMsg = [
			'trans_id.required' => 'Terjadi kegagalan sistem, silahkan coba kembali!',
			'comment.required' => 'Silahkan masukkan ulasan anda!',
			'star.required' => 'Silahkan beri penilaian anda!'
		];
		$validator = \Validator::make($request->all(),$reqData,$resMsg);
	
		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		$auth = new CustomerRepository;
		if($auth->logged()) 
		{
			$customerId = $auth->getId();

			$inserted = Review::insert([
				'review_id' => Uuid::uuid4()->toString(),
				'customer_id' => $customerId,
				'content_type' => 'product',
				'content_id' => $request->trans_id,
				'comments' => $request->comment,
				'star_review' => (int)$request->star,
				'ip' => '',
				'host' => '',
				'status' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]);
		}
		return false;
	}

	public function setting()
	{
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['layout'] = 'setting';

		$auth = new CustomerRepository;
		$data['auth'] = $auth;
		$data['customer'] = $auth->getCustomer(true);

		return view('customer.account', $data);
	}

	public function settingUpdateContact(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'phone' => 'required|min:5',
		]);

		$auth = new CustomerRepository;
		$customer = $auth->getCustomer(true);
		$customerId = $auth->getId();
		$registered = $this->_cust->emailExists($request->email, $customerId);
		if($registered)
		{
			return ['message'=>'Email Anda telah terdaftar', 'errors'=>['Email Anda telah terdaftar']];
		}

		list($phoneCode, $phoneNumber) = $this->_func->splitPhone($request->phone);

		$success = $this->_cust->update([
			'email' => $request->email,
			'phone' => $phoneNumber,
			'phone_code' => $phoneCode,
		], $customerId);
		return $this->_func->resJSON(200, 1);
	}

	public function authRegister(Request $request)
	{
		
		$request->validate([
			'fullname' => 'required|min:3',
			'email' => 'required|email',
			'phone' => 'required|min:5',
			'passwd' => 'required',
			'repasswd' => 'required'
		]);

		$email = $request->post('email');
		$fullname = $request->post('fullname');
		$phone = $request->post('phone');
		$passwd = $request->post('passwd');
		$repasswd = $request->post('repasswd');

		$registered = $this->_cust->emailExists($email);
		if($registered)
		{
			return ['message'=>'Email Anda telah terdaftar', 'errors'=>['Email Anda telah terdaftar']];
		}

		if($passwd !== $repasswd)
		{
			return ['message'=>'Password yang Anda masukkan tidak sesuai.', 'errors'=>['Password yang Anda masukkan tidak sesuai.']];
		}

		list($firstName, $lastName) = $this->_func->splitName($fullname);
		list($phoneCode, $phoneNumber) = $this->_func->splitPhone($phone);

		$activationKey = Str::random(64);
		$customerId = $this->_cust->register([
			'email' => $email,
			'first_name' => $firstName,
			'last_name' => $lastName,
			'phone' => $phoneNumber,
			'phone_code' => $phoneCode,
			'password' => $this->_cust->makePassword($passwd),
			'activation_key' => $activationKey,
			'status' => 1
		]);

		// send email
		if($customerId)
		{	
			$params = [
				'subject'=>'Pendaftaran Pelanggan',
				'template'=>'customer-registration', 
				'email' => $email,
				'name' => implode(' ', [$firstName,$lastName]),
				'phone' => $phoneNumber,
				'phone_code' => $phoneCode,
				'customer_id' => str_pad($customerId, 8, '0', STR_PAD_LEFT),
				'password' => ''
			];
			@Mail::to($email)->send(new \App\Mail\Mailer($params));
			return $this->_func->resJSON(200, 1);
		}
		return ['status'=>'failed','errors'=>['Password yang Anda masukkan tidak sesuai.']];
	}

	public function authLogin(Request $request)
	{

		$email = $request->post('email');
		$password = $request->post('password');
		$cst = new CustomerRepository;

		$cst->setUsername($email)->setPassword($password);
		$valid = $cst->login();
		if($valid)
		{
			return $this->_func->resJSON(200, 1);
		}
		return $this->_func->resJSON(403, 0, null, $cst->getErrors(true));
	}

	public function authLogout(Request $request)
	{
		$cst = new CustomerRepository;
		$cst->logout();
	}

	public function authForgotPassword(Request $request)
	{
		$request->validate([
	        'email' => 'required|email',
	    ]);

	    $email = $request->email;
		$customer = Customer::where('email',$email)->first();
		if(!$customer)
		{
			return $this->_func->resJSON(403, 0, null, 'Email yang anda masukkan tidak terdaftar');
		}

		// init
		$cst = new CustomerRepository;

		// reset password
		$uniqueCode = $this->_func->uniqueCode(64, false);
		$data = [
			'pwd_reset_key' => $uniqueCode,
			'pwd_reset_expiry' => date('Y-m-d H:i:s', strtotime('+3 days'))
		];
		
		Customer::where('email', $email)->update($data);

		$params = [
			'subject'=>'Pemulihan password',
			'template'=>'forgot-password',
			'name'=>trim($customer->first_name . ' ' . $customer->last_name),
			'uniqueCode'=>$uniqueCode,
			'expirydays' => '3'
		];

		@Mail::to($email)->send(new \App\Mail\Mailer($params));

		return $this->_func->resJSON(200, 1, null, 'Silahkan cek email anda dan ikuti instruksi selanjutnya.');
	}

	public function authRecoverPassword(Request $request)
	{
		$request->validate([
			'uniqueCode' => 'required',
			'password' => 'required',
			'repassword' => 'required',
		]);

		$uniqueCode = $request->uniqueCode;
		$password = $request->password;
		$repassword = $request->repassword;

		// if password no match
		if($password != $repassword)
		{
			return $this->_func->resJSON(200, 0, null, 'Password yang Anda masukkan tidak sesuai.');
		}

		$customer = Customer::where('pwd_reset_key', $uniqueCode)->first();
		if($customer)
		{
			if(strtotime($customer->pwd_reset_expiry) > time())
			{
				$data = [
					'password' => $this->_cust->makePassword($password),
					'pwd_reset_expiry' => date('Y-m-d H:i:s')
				];
				Customer::where('pwd_reset_key', $uniqueCode)->update($data);
	
				return $this->_func->resJSON(200, 1, null, 'Pembaruan password berhasil, silahkan login dengan menggunakan password baru anda!');
			} else {
				return $this->_func->resJSON(200, 0, null, 'Token reset password anda sudah kadaluarsa');
			}
		}

		return $this->_func->resJSON(200, 0, null, 'Pembaruan password gagal. Silahkan coba kembali!');
	}

	public function recover(Request $request) {
		// init config
		$config = $this->_config->get();
		$data['config'] = $config;
		$token = $request->token;
		if($token)
		{
			$customer = Customer::where('pwd_reset_key', $token)->first();
			if($customer)
			{
				if(strtotime($customer->pwd_reset_expiry) > time())
				{
					$data['uniqueCode']	= $token;

					return view('customer.recover-password', $data);
				} 
			}
		}
		return redirect('customer/login');
	}

	public function sendVerification(Request $request) 
	{
		$auth = new CustomerRepository;
		
		if($auth->logged()) 
		{
			$id = $auth->getId();
			$customer = $auth->getCustomer(true);
			
			$uniqueCode = $this->_func->uniqueCode(64, false);
			$data = [
				'verification_key' => $uniqueCode,
				'verification_expiry' => date('Y-m-d H:i:s', strtotime('+3 days'))
			];
		
			Customer::where('customer_id', $id)->update($data);

			$params = [
				'subject'=>'Verifikasi Akun visitdairi.com',
				'template'=>'customer-verification',
				'name'=>trim($customer['first_name'] . ' ' . $customer['last_name']),
				'uniqueCode'=>$uniqueCode,
				'expirydays' => '3'
			];
	
			@Mail::to($customer['email'])->send(new \App\Mail\Mailer($params));
	
			return $this->_func->resJSON(200, 1, null, 'Silahkan cek email anda dan ikuti instruksi selanjutnya.');
		}
		$this->_func->resJSON(200, 0);
	}

	public function verification(Request $request)
	{
		// init config
		$config = $this->_config->get();
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		$token = $request->token;
		if($token)
		{
			$customer = Customer::where('verification_key', $token)->first();
			if($customer)
			{
				if(strtotime($customer->verification_expiry) > time())
				{

					$updated = [
						'status' => 2,
						'verificated_date' => date('Y-m-d H:i:s')
					];
					Customer::where('verification_key', $token)->update($updated);
					return view('customer.verification', $data);
				} 
			}
		}
		return redirect('customer/login');
	}
}
