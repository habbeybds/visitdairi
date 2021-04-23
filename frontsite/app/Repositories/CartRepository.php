<?php

namespace App\Repositories;

use Ramsey\Uuid\Uuid;
use App\Models\Cart;

class CartRepository
{

	public function __construct()
	{

	}

	public function sessionCart($newsession = false)
	{
		$session = session('__cart');
		if(!$session || $newsession)
		{
			$uuid = Uuid::uuid4()->toString();
			session(['__cart'=>$uuid]);
		}
		return session('__cart');
	}

	public function getData($cartKey)
	{
		return Cart::where('cart_key', $cartKey)->first();
	}
}