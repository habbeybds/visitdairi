<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\EmailRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Province;
use App\Models\Partner;
use App\Models\PartnerImage;
use Mail;

class PartnerController extends BaseController
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

	public function registerPartner(Request $request)
	{

		$validator = \Validator::make($request->all(),[
			'perusahaan' => 'required',
			'nama' => 'required|min:3',
			'telp' => 'required',
			'email' => 'required|email',
			'ktp' => 'required',
			'province' => 'required',
			'city' => 'required',
			'address' => 'required',
			'ffoto' => 'required',
			'fktp' => 'required',
			'fnpwp' => 'required'
		],[
			'perusahaan.required' => 'Nama perusahaan wajib diisi.',
			'email.required' => 'Email wajib diisi!',
			'email.email' => 'Format penulisan email tidak sesuai',
			'nama.required'=> 'Nama wajib diisi!',
			'nama.min' => 'Nama minimal 3 karakter',
			'ktp.required'=> 'Nomor KTP wajib diisi!',
			'telp.required'=> 'Telepon wajib diisi!',
			'province.required'=> 'Silahkan pilih propinsi',
			'city.required'=> 'Silahkan pilih kota',
			'address.required' => 'Alamat wajib diisi',
			'ffoto.required' => 'Silahkan upload scan foto',
			'fktp.required' => 'Silahkan upload scan KTP',
			'fnpwp.required' => 'Silahkan upload scan NPWP'
		]);

		$name = $request->nama;
		$email = $request->email;

		// return invalid request
		if ($validator->fails()) {
		   return response()->json($validator->errors(), 422);
		}

		$existEmail = Partner::where('email', $email)->first();
		if($existEmail)
		{
			return response()->json(['Email sudah terdaftar.'], 402);
		}

		$dataPartner = [
		 	'company_name' => $request->perusahaan,
		  	'fullname' => $request->nama,
		  	'phone' => $request->telp,
		  	'email' => $request->email,
		  	'id_number' => $request->ktp,
		  	'province_id' => $request->province,
		  	'city_id' => $request->city,
		  	'subdistrict_id' => $request->subdistrict,
		  	'address' => $request->address,
		  	'status' => 0,
		  	'created_at' => date('Y-m-d H:i:s'),
		  	'modified_at' => date('Y-m-d H:i:s'),
		];
		$partnerId = Partner::insertGetId($dataPartner);

		if($partnerId)
		{
			$partner = Partner::find($partnerId);
			
			$dataImages = [];
			$ImgPath = 'partner/'.$partnerId.'/';

			if($request->has('ffoto')){
				$file = $request->file('ffoto');
				$ImgName = 'photo-' . $partnerId . '.' . $file->extension();
				$path = $file->storeAs($ImgPath,$ImgName, 'frontsite');
				if($path){
					$partner->profile_file = $ImgPath.$ImgName;
				}
			}
			
			if($request->has('fktp')){
				$file = $request->file('fktp');
				$ImgName = 'id-' . $partnerId . '.' . $file->extension();
				$path = $file->storeAs($ImgPath,$ImgName, 'frontsite');
				if($path){
					$partner->id_file = $ImgPath.$ImgName;
				}
			}
			
			if($request->has('fnpwp')){
				$file = $request->file('fnpwp');
				$ImgName = 'npwp-' . $partnerId . '.' . $file->extension();
				$path = $file->storeAs($ImgPath,$ImgName, 'frontsite');
				if($path){
					$partner->npwp_file = $ImgPath.$ImgName;
				}
			}

			$partner->save();
			
			$params = [
				'subject'=>'Pendaftaran Partner',
				'template'=>'partner-registration',
				'name' => $name
			];

			// send email
			@Mail::to($email)->send(new \App\Mail\Mailer($params));

			return [
				'status' => 'success',
				'message' => 'Pendaftaran anda sebagai partner <b>visitdairi.com</b> telah berhasil. Mohon tunggu konfirmasi selanjutnya dari admin VisitDairi.com'
			];
		}
		

		return $request->all();
	}

}
