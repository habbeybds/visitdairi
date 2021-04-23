<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = "subdistricts";
    protected $primaryKey = 'subdistrict_id';
    public $timestamps = false;
    protected $fillable = ['city_id','subdistrict_name'];

    public function partner()
    {
        return $this->hasMany(Partner::Class,'subdistrict_id');
    }
    
    public function city()
    {
        return $this->belongsTo(City::Class,'city_id');
    }
}