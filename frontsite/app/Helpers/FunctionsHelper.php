<?php

namespace App\Helpers;

class FunctionsHelper
{
	const monthsID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
	const daysID = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
	
	public function __construct()
	{
		
	}
	
	public function uniqueCode($l = 8, $uppercase = true) {
		$string = md5(uniqid(mt_rand(), true)) . sha1(mt_rand());
    	$string = substr($string, 0, $l);
		if($uppercase)
		{
			$string = strtoupper($string);
		}
		return $string;
	}

	public function slugify($string)
	{
		$string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
		$string = trim($string, '-');
		$string = strtolower($string);
		return $string;
	}

	public function dateIDFormat($strdate)
	{
		$date = strtotime($strdate);
		return implode(' ', [
			date('d', $date),
			static::monthsID[date('n', $date) - 1],
			date('Y', $date)
		]);
	}

	public function nightOfDays($date1, $date2)
	{
		if($date1 == $date2)
		{
			return 1;
		}

		$date1 = date_create($date1);
		$date2 = date_create($date2);
		$date = date_diff($date1, $date2);
		$days = (int)$date->format("%R%a");
		if($days > 0) {
			return $days;
		}
		return 0;
	}

	public function scheduleStringDate($stringDate)
	{
		$date = strtotime($stringDate);
		$stringDate = [
			static::daysID[date('w',$date)].',',
			date('d',$date),
			static::monthsID[date('n',$date)-1],
			date('Y',$date)
		];
		return implode(' ', $stringDate);
	}

	public function resJSON($code, $status, $data = [], $message = '')
	{
		$response = [
			'status'    => $status ? 'success' : 'error',
            'code'      => $code,
            'datetime'  => date("Y-m-d\TH:i:s\Z"),
            'timestamp' => time(),
            'message'   => $message
		];
		if(!empty($data)) {
			$response['data'] = $data;
		}
		return $response;
	}

	public function sort($dataArray, $type)
	{
		return $dataArray;
	}

	public function splitName($name)
	{
		$firstName = '';
		$lastName = '';
		$words = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
		if(count($words) == 1) {
			$firstName = implode(' ',$words);
			$lastName = '';
		} else {
			$lastName = array_pop($words);
			$firstName = implode(' ', $words);
		}
		return [$firstName,$lastName];
	}

	public function splitPhone($phone)
	{
		$phoneCode = '62';
		$phoneNumber = '';
		$phone = preg_replace('/[^0-9\+]/', '', $phone);
		if(substr($phone, 0,1) === '0') {
			$phoneCode = '62';
			$phoneNumber = substr($phone, 1);
		} elseif(substr($phone, 0,2) === '62') {
			$phoneCode = '62';
			$phoneNumber = substr($phone, 2);
		} elseif(substr($phone, 0,3) === '+62') {
			$phoneCode = '62';
			$phoneNumber = substr($phone, 3);
		} else {
			$phoneNumber = $phone;
		}
		return [$phoneCode, $phoneNumber];
	}
}