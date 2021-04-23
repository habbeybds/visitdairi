<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Review extends Model
{
    protected $table = 'reviews';
    public $timestamps = false;

    public function getReview($contentType, $contentId)
    {
    	$prefix = DB::getTablePrefix();
    	$reviews = $this->select([
    		'reviews.review_id AS id',
    		'reviews.comments',
    		'reviews.star_review',
    		DB::raw('CONCAT("images/customers/",'.$prefix.'customers.customer_id,".png") AS avatar'),
    		DB::raw('TRIM(CONCAT(first_name," ",last_name)) as customer'),
    	])
        ->join('customers', 'customers.customer_id', 'reviews.customer_id')
        ->where('content_type', $contentType)
        ->where('content_id', $contentId)
        ->get();

    	if($reviews)
    	{
    		return $reviews->toArray();
    	}
    	return [];
    }
}