<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransPayment extends Model
{
    protected $table = 'trans_payment';
    protected $primaryKey = 'trans_payment_id';
    public $timestamps = false;
    protected $fillable = ['trans_id','pay_channel_id','currency','total_price','total_fee','grand_total','mdr','card_number','va_number','bank','payment_link','status','created_at','modified_at'];
    
    public function trans()
    {
        return $this->belongsTo(Trans::Class,'trans_id');
    }

    public function paychannel()
    {
        return $this->belongsTo(PayChannel::Class,'pay_channel_id');
    }

    public function transpaymentrequest()
    {
        return $this->hasMany(TransPaymentRequest::Class,'trans_payment_request_id');
    }

    public function transpaymentnotify()
    {
        return $this->hasMany(TransPaymentNotify::Class,'trans_payment_notify_id');
    }

}