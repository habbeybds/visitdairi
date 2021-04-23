<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trans extends Model
{
    protected $table = 'trans';
    protected $primaryKey = 'trans_id';
    public $timestamps = false;
    protected $fillable = ['partner_id','invoice_number','invoice_date','trans_code','customer_id','customer_name','customer_phone','customer_email',
                            'total_payment','timelimit','payment_date','status','claim_status','shipment_status','notes','created_at','modified_at'];
    
    public function partner()
    {
        return $this->belongsTo(Partner::Class,'partner_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::Class,'customer_id');
    }

    public function transdet()
    {
        return $this->hasMany(TransDet::Class,'trans_det_id');
    }

    public function transpayment()
    {
        return $this->hasMany(TransPayment::Class,'trans_payment_id');
    }

}