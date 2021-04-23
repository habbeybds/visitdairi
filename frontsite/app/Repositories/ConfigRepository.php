<?php

namespace App\Repositories;
use App\Repositories\CacheRepository;
use App\Models\Config;
use App\Models\Destination;
use App\Models\DestinationImage;
use Log;

class ConfigRepository
{

	private $configCache = false;
	private $cache;

	const limitCache = 60480000;

	protected function updateCache()
	{
		$data = [];
		$config = Config::where('autoload', 1)->get();
		
		if($config)
		{
			foreach($config as $cfg) 
			{
				$value = $cfg->value;
				if($cfg->serialize) 
				{
					$value = @unserialize($cfg->value);
					if(!$value) {
						Log::error('UPDATE CACHE ERROR: '. $cfg->value);
					}
				}
				
				$data[$cfg->type][$cfg->name] = $value;
			}
			$this->cache->save('config', $data, static::limitCache);
			$this->cache->clear('destinations');
		}
		return $data;
	}

	public function __construct(CacheRepository $cache)
	{
		$this->cache = $cache;
		$this->configCache = $this->cache->get('config');
	}

	public function get($name = '*', $type = 'webconfig')
	{
		if(!$this->configCache)
		{
			$this->configCache = $this->updateCache();
		}

		$config = $this->configCache;
		if(isset($config[$type]))
		{
			$config = $config[$type];
			if($name !== '*')
			{
				return isset($config[$name]) ? $config[$name] : []; 
			}
			return $config;
		}
		
		return [];
	}

	public function refresh()
	{
		return $this->updateCache();
	}

	public function update($fields, $value = '', $type = 'webconfig')
	{
		if(is_array($fields))
		{	
			foreach($fields as $key=>$item)
			{
				$data['value'] = $item;
				$data['serialize'] = 0;
				if(is_array($item)) 
				{
					$data['value'] = serialize($item);
					$data['serialize'] = 1;	
				}
				Config::where('type', $type)->where('name', $key)->update($data);
			}
		} 
		// if single update
		else {
			$data['value'] = $value;
			$data['serialize'] = 0;
			if(is_array($value)) 
			{
				$data['value'] = serialize($value);
				$data['serialize'] = 1;	
			}
			Config::where('type', $type)->where('name', $fields)->update($data);
		}
		$this->configCache = $this->updateCache();
		return true;
	}

	public function buildFooterTopDestination()
	{
		$limit = 6;
		$destination = Destination::inRandomOrder()
			->select([
				'destination_id AS id',
				'slug',
				'title',
				'image_thumbnail'
			])
			->limit($limit)
			->get();

		return $destination;
	}

}