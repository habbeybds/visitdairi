<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostingCategory extends Model
{
    protected $table = 'post_categories';
    public $timestamps = false;

    public function getSitebar()
    {
    	$html = '';
    	$categories = $this->select(['post_category_id AS id', 'category_name AS name', 'category_slug AS slug'])->get();
    	if($categories) {
    		foreach($categories as $key=>$cat)
    		{
    			$html .= '<li><a href="'.url('post/c/'.$cat->id.'-'.$cat->slug).'"> <i class="fas fa-angle-right"></i> '.$cat->name. '</a> </li>';
    		}
    	}

    	$html = empty($html) ? '<li><small>Belum memiliki kategori</small></li>' : $html; 
    	return $html;
    }
}