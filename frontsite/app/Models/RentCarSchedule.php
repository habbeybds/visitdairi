<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentCarSchedule extends Model
{
    protected $table = 'rent_car_schedules';
    protected $primaryKey = 'rent_car_schedule_id';
    public $timestamps = false;
    protected $fillable = ['rent_car_id','schedule_date','partner_price','public_price','outstanding_capacity','created_at','modified_at'];
    
    public function rentcar()
    {
        return $this->belongsTo(RentCar::Class,'rent_car_id');
    }

}