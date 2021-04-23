<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiteSearch extends Model
{
    protected $table = "lite_search";
    protected $primaryKey = 'search_id';
    public $timestamps = false;
    protected $fillable = ['content_route','content_type','content_title','content_category','content_desc','counter','content_id','content_status'];
}