<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    public $timestamps = false;
    protected $fillable = [
        //'cart_id',
    	// 'cart_key',
	  	// 'customer_id',
	  	// 'product_id',
	  	'quantity',
        //'created_at',
        'updated_at',
    ];

}