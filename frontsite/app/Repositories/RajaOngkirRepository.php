<?php

namespace App\Repositories;

use App\Repositories\CacheRepository;

class RajaOngkirRepository
{


	public function __construct()
	{

	}

	protected function send($actionUrl, $param = [], $method = 'GET')
	{

		$options = [
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_SSL_VERIFYHOST => 0,
		  	CURLOPT_SSL_VERIFYPEER => 0,
		  	CURLOPT_CUSTOMREQUEST => $method,
		  	CURLOPT_HTTPHEADER => [
		    	"key: ". config('constants.RAJAONGKIR.KEY')
		  	],
		];

		$Url = config('constants.RAJAONGKIR.BASE_URL') . $actionUrl;
		if(!empty($param)) {
			if($method == 'GET') {
				$Url .= '?'.http_build_query($param);	
			} else if($method == 'POST') {
				$options[CURLOPT_POSTFIELDS] = http_build_query($param);
			}
		}
		$options[CURLOPT_URL] = $Url;

		// curl init
		$curl = curl_init();
		curl_setopt_array($curl, $options);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getProvince($param = [])
	{
		$response = $this->send('province', $param);
		$response = json_decode($response, true);
		return $response;
	}

	public function getCity($param = [])
	{
		$response = $this->send('city', $param);
		$response = json_decode($response, true);
		return $response;
	}

	public function getSubdistrict($param = [])
	{
		$response = $this->send('subdistrict', $param);
		$response = json_decode($response, true);
		return $response;
	}

	public function currency() 
	{
		$data = [];
		$response = $this->send('currency', $data);
		$response = json_decode($response, true);
		return $response;
	}

	public function getCost($origin, $originType, $destination, $destinationType, $weight = 1000, $courier)
	{
		$data = [
			'origin'=>$origin,
			'originType'=>$originType,
			'destination'=>$destination,
			'destinationType'=>$destinationType,
			'weight'=>$weight,
			'courier'=>$courier
		];
		$response = $this->send('cost', $data, 'POST');
		$response = json_decode($response, true);
		return $response;
	}

	public function waybill($waybill, $courier)
	{
		$data = [
			'waybill' => $waybill,
			'courier' => $courier
		];
		$response = $this->send('waybill', $data, 'POST');
		$response = json_decode($response, true);
		return $response;
	}
}
