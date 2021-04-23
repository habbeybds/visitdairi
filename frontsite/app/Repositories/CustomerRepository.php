<?php

namespace App\Repositories;

use App\Repositories\CacheRepository;
use App\Models\Customer;
use App\Models\CustomerAddress;

class CustomerRepository
{

	private $username = '';
	private $password = '';
	private $customerId = '';
	private $sessionId = '';
	private $_customer = false;
	private $_errors = [];

	public function __construct()
	{
		$this->customerId = '';
		$this->sessionId = '';
		$this->_customer = false;
		$this->_errors = [];
		$this->getCustomerSession();
	}

	public function logged()
	{
		// check has logged
		return $this->_customer !== false;
	}

	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	public function login()
	{
		return $this->doLogin();
	}

	public function logout()
	{
		return $this->doLogout();
	}

	public function getId()
	{
		return $this->customerId;
	}

	public function getSession()
	{
		return $this->sessionId;
	}

	public function getName()
	{
		if($customer = $this->_customer)
		{
			return implode(' ', [$customer['first_name'],$customer['last_name']]); 
		}
		return false;
	}

	public function getCustomer($showDetail = false, $showAllStatus = false)
	{
		if(!$showDetail && !$showAllStatus)
		{
			return $this->_customer; 
		}

		// show
		$customer = Customer::where('customer_id', $this->customerId);
		$customer->select([
			'salutation',
			'customer_id',
			'email',
			'first_name',
			'last_name',
			'phone',
			'phone_code',
			'dob',
			'gender',
			'address',
			'postcode',
			'province_id',
  			'city_id',
  			'subdistrict_id',
			'status',
			'created_at',
			'updated_at'
		]);
		if(!$showAllStatus)
		{
			$customer->whereIn('status', [1,2]);
		}
		$customers = $customer->first();
		if($customers)
		{
			$customers = $customers->toArray();
			$address = CustomerAddress::where('customer_id', $this->customerId)->first();
			//if(!$address) $address = false;
			//$customers['address'] = $address;

			return $customers;
		}
		return false;
	}

	public function getErrors($isString = false) 
	{
		if($isString) {
			return implode(', ', $this->_errors);
		}
		return $this->_errors;
	}

	public function emailExists($email, $exceptId = false)
	{
		$customer = Customer::where('email', $email);
		if($exceptId)
		{
			$customer->where('customer_id', '<>', $exceptId);
		}
		$customer = $customer->where('status', '<>', '-1')->first();
		if($customer)
		{
			return true;
		}
		return false;
	}

	public function register($data)
	{
		$customerId = Customer::insertGetId($data);
		return $customerId;
	}

	public function update($data, $customerId) 
	{
		$success = Customer::where('customer_id', $customerId)
			->update($data);
		return true;
	}

	public function makePassword($plainText)
	{
		$salt = 'secret';
        $md5password = md5($plainText);
        $sha1salt = sha1($salt);
        return base64_encode($md5password . $sha1salt);
	}

	public function matchPassword($password, $hashedPassword)
	{
		$salt = 'secret';
        $md5password = md5($password);
        $sha1salt = sha1($salt);
        $string = base64_encode($md5password . $sha1salt);
        return ($string === $hashedPassword);
	}

	protected function getCustomerSession()
	{
		$session = session('__customer');
		if($session && isset($session['token'])) 
		{
			if(isset($session['expiry']) && (strtotime($session['expiry']) > time()))
			{
				$token = base64_decode($session['token']);
				list($uuid, $sessionId, $customerId) = explode('##', $token);
				$this->customerId = $customerId;
				$this->sessionId = $sessionId;
				$this->_customer = unserialize($session['data']);
			}
		}
		return false;
	}

	protected function doLogin()
	{
		$customer = Customer::whereIn('status', [1,2])->where('email', $this->username)->first();
		if($customer)
		{
			$matched = $this->matchPassword($this->password, $customer->password);
			if($matched)
			{
				$uuid = $this->uuid();
				$sessionId = session_id();
				$customerId = $customer->customer_id;
				$tokenKey = base64_encode($uuid . '##' . $sessionId . '##' . $customerId);
				$expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
				$customerData = [
					'email' => $customer->email,
					'first_name' => $customer->first_name,
					'last_name' => $customer->last_name,
					'phone' => $customer->phone,
					'phone_code' => $customer->phone_code
				];
				$cacheData = [
					'token' => $tokenKey,
					'expiry' => $expiry,
					'data' => serialize($customerData)
				]; 

				$this->customerId = $customerId;
				$this->_customer = $customerData;
				session(['__customer' => $cacheData]);
				return true;
			}
			$this->_errors[] = 'Password yang Anda masukkan tidak sesuai.';
			return false;
		}
		$this->_errors[] = 'Anda belum terdaftar, silahkan daftar menjadi customer VISITDAIRI.';
		return false;
	}	

	protected function doLogout()
	{
		session()->flush();
	}

	protected function uuid()
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
	}
}