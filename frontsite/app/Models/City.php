<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = "cities";
    protected $primaryKey = 'city_id';
    public $timestamps = false;
    protected $fillable = ['province_id','type','city_name','postal_code','created_at','modified_at'];

    public function partner()
    {
        return $this->hasMany(Partner::Class,'city_id');
    }
    
    public function subdistrict()
    {
        return $this->hasMany(Subdistrict::Class,'subdistrict_id');
    }
    
    public function province()
    {
        return $this->belongsTo(Province::Class,'province_id');
    }
}