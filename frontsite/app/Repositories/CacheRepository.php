<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class CacheRepository
{

	/**
     * Path to the cache directory
     * @var string
     */
    private $cacheDir;
    const prefix = '__cache_';

	public function __construct()
	{
		$this->setCacheDir();
	}

	public function get($key, $meta = false)
	{
		$cacheFiles = glob($this->cacheDir . static::prefix . $key . '.*');
        foreach($cacheFiles as $file) {
            if(is_file($file)) {
            	$ext = (int) pathinfo($file, PATHINFO_EXTENSION);
            	if($ext > time()) {
            		$cacheContent = @file_get_contents($file);
            		$data = unserialize($cacheContent);
            		return $meta ? $data : $data['data'];
            	}
                @unlink($file); //delete file
            }
        }
        return false;
	}

	public function save($key, $data, $expiration = 86400, $permanent = false)
	{
		if(!is_string($key)) {
            throw new \InvalidArgumentException('$key must be a string, got type "' . get_class() . '" instead');
        }

        $expiry = time() + (int)$expiration;
        $storeData = [
        	'expire' => date('Y-m-d H:i:s', $expiry),
        	'permanent' => $permanent,
        	'data-type' => gettype($data),
        	'data' => $data
        ];

        if (!file_exists($this->cacheDir))
            @mkdir($this->cacheDir);

        $this->clear($key);
       	$storeData['hash-sum'] = md5(serialize($storeData));
       	$cacheFilePath = $this->cacheDir . static::prefix . $key . '.' . $expiry;
       	$cacheData = serialize($storeData);
       	$success = file_put_contents($cacheFilePath, $cacheData) !== false;

        if (!$success)
            throw new \Exception("Cannot save cache");

        return $storeData['hash-sum'];
	}

	public function clear($name = '')
	{
		$counter = 0;
        $cacheFiles = glob($this->cacheDir . static::prefix . $name . '.*');
        foreach($cacheFiles as $file){
            if(is_file($file)) {
                @unlink($file); //delete file
            }
            $counter++;
        }
        return $counter;
	}

	private function setCacheDir()
	{
		$baseDir = storage_path('cache') . '/';
  		$this->cacheDir = $baseDir;
	}
}