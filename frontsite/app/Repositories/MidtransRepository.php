<?php

namespace App\Repositories;

use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Snap;
use App\Models\TransPayment;
use App\Models\TransPaymentRequest;
use Log;

class MidtransRepository
{

	private $orderId;
	private $grossAmount;
	private $paymentChannel;
	private $customerDetails = false;
	private $itemDetails = false;

	public function __construct()
	{

	}

	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
		return $this;
	}

	public function setTransPaymentId($transPaymentId)
	{
		$this->transPaymentId = $transPaymentId;
		return $this;
	}

	public function setGrossAmount($grossAmount)
	{
		$this->grossAmount = $grossAmount;
		return $this;
	}

	public function setChannel($paymentChannel)
	{
		$this->paymentChannel = $paymentChannel;
		return $this;
	}

	public function setCustomerDetails($detail)
	{
		$this->customerDetails = $detail;
		return $this;
	}

	public function setExpiry($expiry)
	{
		$this->expiry = $expiry;
		return $this;
	}

	public function getSnapToken() 
	{
		Config::$serverKey = config('constants.MIDTRANS.SERVER_KEY');

		Config::$isProduction = false;
		if(config('constants.MIDTRANS.ENVIRONMENT') == 'production'){
			Config::$isProduction = true;
		}

		Config::$isSanitized = true;

        // Enable 3D-Secure
        Config::$is3ds = true;

		$params['transaction_details'] = [
		  	'order_id' => $this->orderId,
		   	'gross_amount' => $this->grossAmount,
		];

		if($this->customerDetails)
		{
			$params['customer_details'] = $this->customerDetails;
		}

		if($this->itemDetails)
		{
			$params['item_details'] = $this->itemDetails;
		}

		if($this->expiry)
		{
			$params['expiry'] = $this->expiry;
		}

		$snapToken = Snap::getSnapToken($params);

		$transpayment = TransPayment::find($this->transPaymentId);
		// log get snap token transaction
		$dataLogRequest = [
		  	'trans_payment_id' => $this->transPaymentId,
		  	'param_request' => json_encode($params),
		  	'param_response' => $snapToken,
		  	'token' => $snapToken,
		  	'redirect_url' => config('constants.MIDTRANS.URL') . $snapToken . '#/' . $transpayment->paychannel->link_rewrite,
		  	'created_at' => date('Y-m-d H:i:s'),
		  	'modified_at' => date('Y-m-d H:i:s')
		];
		@TransPaymentRequest::insert($dataLogRequest);

		return $snapToken;
	}

	public function getNotification($data)
	{

	}

	public function transactionCharge($data)
	{
		$params = $this->paymentCreditCard($data);
		$params['transaction_details'] = [
		  	'order_id' => $this->orderId,
		   	'gross_amount' => $this->grossAmount,
		];

		if($this->customerDetails)
		{
			$params['customer_details'] = $this->customerDetails;
		}

		$response = CoreApi::charge($params);
		Log::info(json_encode($response));
		return $response;
	}

	public function validSignature($signature, $orderId, $statusCode, $grossAmount)
	{
		$serverKey = config('constants.MIDTRANS.SERVER_KEY');
		$input = $orderId.$statusCode.$grossAmount.$serverKey;
		$key = openssl_digest($input, 'sha512');
		return $key == $signature;
	}

	// Notification Status
	private function notificationStatus200()
	{

	}

	private function notificationStatus201()
	{
		
	}

	private function notificationStatus202()
	{
		
	}
	// Notificaion Status End

	private function paymentCreditCard($tokenId)
	{
		$params['payment_type'] = 'credit_card';
		$params['credit_card'] = [
			'token_id' => $tokenId,
			'authentication' => true
		];
		return $params;
	}

	private function paymentBankTransfer($bank)
	{
		$params['payment_type'] = 'bank_transfer';
		$params['bank_transfer'] = [
      		'bank' => $bank
  		];
  		return $params;
	}

	private function paymentType($channel)
	{
		$types = [
			'credit_card' => 'CreditCard',
			'bank_transfer' => 'BankTransfer'
		];
		if(in_array($channel, $types))
		{
			return $types[$channel];	
		}
		return [];
	} 

}