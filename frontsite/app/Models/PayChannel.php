<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayChannel extends Model
{
    protected $table = 'pay_channels';
    protected $primaryKey = 'pay_channel_id';
    public $timestamps = false;
    protected $fillable = ['name','link_rewrite','logo_url','pay_fee_amount','pay_fee_percentage','description','timelimit','published','created_at','modified_at'];
}