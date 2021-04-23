<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    public $timestamps = false;

    public function getContent($type, $key)
    {
    	$page = $this->where('published', 1)->where($type, $key);
    	return $page->first();
    }
}