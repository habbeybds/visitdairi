<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransDetail extends Model
{
    protected $table = 'trans_det';
    protected $primaryKey = 'trans_det_id';
    public $timestamps = false;
    protected $fillable = ['trans_id','product_id','product_type_id','product_detail','currency','price','qty','subtotal','created_at','modified_at'];
    
    public function trans()
    {
        return $this->belongsTo(Trans::Class,'trans_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::Class,'product_id');
    }

    public function producttype()
    {
        return $this->belongsTo(ProductType::Class,'product_type_id');
    }

}