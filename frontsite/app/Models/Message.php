<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";
    protected $primaryKey = 'message_id';
    public $timestamps = false;
    protected $fillable = ['name','phone','email','subject','message','reply','status','reply_at','created_at','modified_at'];

}
