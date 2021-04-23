<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = "provinces";
    protected $primaryKey = 'province_id';
    public $timestamps = false;
    protected $fillable = ['name','created_at','modified_at'];

    public function partner()
    {
        return $this->hasMany(Partner::Class,'province_id');
    }
                    
    public function city()
    {
        return $this->hasMany(City::Class,'province_id');
    }
}