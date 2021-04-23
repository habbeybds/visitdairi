<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Models\Page;
use App\Models\PostingCategory;
use App\Helpers\FunctionsHelper;

class PageController extends BaseController
{

	protected $_config = null;
	protected $_func = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs; 
		$this->_func = $functions; 
	}

	public function index($pageSlug, PostingCategory $cat)
	{

		// get config
		$config = $this->_config->get();  
		$data['config'] = $config;
		$data['auth'] = new CustomerRepository;
		
		$page = new Page;
		$pages = $page->getContent('slug', $pageSlug);
		//return $pages;
		if($pages)
		{
			$layout = !empty($pages->layout) ? $pages->layout : 'default';

			$data['categories'] = $cat->getSitebar();
			$data['page'] = $pages;	

			return view('layouts.'.$layout, $data);
		}
		return view('errors.404', $data);
	}
}
