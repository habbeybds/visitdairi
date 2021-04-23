<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;


class TestController extends BaseController
{

	public function mailsender()
	{		
	$data = [
		'subject'=>'Pendaftaran Pelanggan',
		'template'=>'customer-registration', 
		'name'=>"Virat Gandhi",
		'customer_id'=>'1111',
	];
   
    Mail::to('roywae@gmail.com')->send(new \App\Mail\Mailer($data));
		  
	return 'Email sent Successfully';

	}
}