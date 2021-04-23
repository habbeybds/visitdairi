<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $table = "car_brands";
    protected $primaryKey = 'car_brand_id';
    public $timestamps = false;
    protected $fillable = ['name','created_at','modified_at'];

    public function carmodel()
    {
        return $this->hasMany(CarModel::Class,'car_brand_id');
    }
    
    public function rentcar()
    {
        return $this->hasMany(RentCar::Class,'car_brand_id');
    }
    
}
