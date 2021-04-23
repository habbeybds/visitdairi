<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\CacheRepository;
use DB;

class Destination extends Model
{
    protected $table = 'destinations';
    public $timestamps = false;

    protected function getDataList()
    {
    	$prefix = DB::getTablePrefix();
    	$destinations = $this->select([
			'destinations.destination_id AS id',
			'destinations.name',
			'destinations.title',
			'destinations.slug',
			'destinations.published',
			DB::raw('COALESCE('.$prefix.'destination_images.image_url, "") AS image'),
			DB::raw('COALESCE(AVG('.$prefix.'reviews.star_review), 0) AS rating')
		])
		->leftjoin('reviews', 'reviews.content_id','destinations.destination_id')
		->leftjoin('destination_images', 'destination_images.destination_id', 'destinations.destination_id')
		->groupBy('destinations.destination_id')
		->get();
		if($destinations)
		{
			$result = [];
			foreach($destinations as $d)
			{
				$result[] = [
					'id' => $d->id,
					'name' => $d->name,
					'title' => $d->title,
					'slug' => $d->slug,
					'published' => $d->published,
					'image' => config('constants.UPLOAD_PATH').$d->image,
					'rating' => $d->rating
				];
			}
			return $result;
		}
		return false;
    }

    public function getList($nocache = false)
    {

    	if(!$nocache)
    	{
    		$cache = new CacheRepository;
    		$destinations = $cache->get('destinations');
    		if(!$destinations) {
    			$destinations = $this->getDataList();
    			$cache->save('destinations', $destinations);
    		}
    		return $destinations;
    	}

    	$destinations = $this->getDataList();
    	return $destinations;
    }
}