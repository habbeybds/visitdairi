<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\MemberRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Subscribe;
use Mail;

class SubscribeController extends BaseController
{

	public function __construct()
	{

	}

	public function submit(Request $request)
	{
		$request->validate([
	        'email' => 'required|email',
	    ]);

	    $email = $request->email;
		Subscribe::insertOrIgnore(['email'=>$email]);
		
		$params = [
			'subject'=>'Pendaftaran Berlangganan',
			'template'=>'subscribe'
		];
		@Mail::to($email)->send(new \App\Mail\Mailer($params));

		return [
			'status' => 'success',
			'data' => []
		];
	}

}