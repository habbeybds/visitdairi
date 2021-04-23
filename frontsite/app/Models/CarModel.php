<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $table = "car_models";
    protected $primaryKey = 'car_model_id';
    public $timestamps = false;
    protected $fillable = ['car_brand_id','name','passenger_capacity','car_image','created_at','modified_at'];

    public function rentcar()
    {
        return $this->hasMany(RentCar::Class,'car_model_id');
    }
    
    public function carbrand()
    {
        return $this->belongsTo(CarBrand::Class,'car_brand_id');
    }
    
}
