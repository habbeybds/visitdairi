<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $table = 'product_tags';
    protected $primaryKey = 'product_tag_id';
    public $timestamps = false;
    protected $fillable = ['product_id','tag_id','created_at','modified_at'];
    
    public function product()
    {
        return $this->belongsTo(Product::Class,'product_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::Class,'tag_id');
    }

}