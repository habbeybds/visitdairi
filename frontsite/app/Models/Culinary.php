<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Culinary extends Model
{
    protected $table = 'culinaries';
    protected $primaryKey = 'culinary_id';
    public $timestamps = false;
    protected $fillable = ['product_id','opening_hours','menu','price_range','created_at','modified_at'];
    
    public function product()
    {
        return $this->belongsTo(Product::Class,'product_id');
    }

    public function culinaryimage()
    {
        return $this->hasMany(CulinaryImage::Class,'culinary_id');
    }

}