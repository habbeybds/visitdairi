<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = "partners";
    protected $primaryKey = 'partner_id';
    public $timestamps = false;
    protected $fillable = ['company_logo','company_thumbnail','partner_code','company_name','company_overview','slug','url_map','fullname','phone','email','id_number','province_id','city_id','subdistrict_id','address',
                        'profile_file','id_file','npwp_file','partner_account_id','user_id','approval_id','approval_date','approval_result','status','created_at','modified_at'];

    public function province()
    {
        return $this->belongsTo(Province::Class,'province_id');
    }
    
    public function city()
    {
        return $this->belongsTo(City::Class,'city_id');
    }
    
    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::Class,'subdistrict_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::Class,'user_id');
    }
    
    public function partneraccount()
    {
        return $this->belongsTo(PartnerAccount::Class,'partner_account_id');
    }
    
    public function partnerimage()
    {
        return $this->hasMany(PartnerImage::Class,'partner_id');
    }
    
    public function product()
    {
        return $this->hasMany(Product::Class,'partner_id');
    }
    
}
