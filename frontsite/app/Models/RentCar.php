<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentCar extends Model
{
    protected $table = 'rent_cars';
    protected $primaryKey = 'rent_car_id';
    public $timestamps = false;
    protected $fillable = ['product_id','car_brand_id','car_model_id','is_driver','total_vehicle','cooperation_type','commission_type','commission_value','markup_type','markup_value',
    'default_partner_price','default_publish_price','url_map','tnc_desc','price_include','price_exclude','created_at','modified_at'];
    
    public function product()
    {
        return $this->belongsTo(Product::Class,'product_id');
    }

    public function carbrand()
    {
        return $this->belongsTo(CarBrand::Class,'car_brand_id');
    }

    public function carmodel()
    {
        return $this->belongsTo(CarModel::Class,'car_model_id');
    }

    public function rentcarschedule()
    {
        return $this->hasMany(RentCarSchedule::Class,'rent_car_id');
    }

}