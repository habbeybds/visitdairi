<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webstats extends Model
{
    protected $table = 'webstats';
    public $timestamps = false;

    protected $fillable = ['id','logdate','ip','useragent','device','pagevisit','pagehit','referer','updated_at'];
}