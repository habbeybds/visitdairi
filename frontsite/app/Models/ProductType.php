<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\CacheRepository;

class ProductType extends Model
{
    protected $table = 'product_types';
    public $timestamps = false;

    protected function getDataList()
    {
    	$productType = $this->select([
			'product_type_id AS id',
		 	'name',
		  	'slug',
		  	'description',
		  	'template',
		])
		->get();
		if($productType)
		{
			$result = [];
			foreach($productType as $p)
			{
				$result[] = [
					'id' => $p->id,
					'name' => $p->name,
					'slug' => $p->slug,
					'description' => $p->description,
					'template' => $p->template,
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
    		$productType = $cache->get('product_types');
    		if(!$productType) {
    			$productType = $this->getDataList();
    			$cache->save('product_types', $productType);
    		}
    		return $productType;
    	}

    	$productType = $this->getDataList();
    	return $productType;
    }
}