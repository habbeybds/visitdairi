<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Helpers\FunctionsHelper;
use Mail;

class StoreController extends BaseController
{
    
    protected $_config = null;
	protected $_func = null;

	public function __construct(ConfigRepository $configs, FunctionsHelper $functions)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
	}


    public function index(Request $request)
	{   
    
        // get config
		$config = $this->_config->get();
		$data['config'] = $config;
        $data['auth'] = new CustomerRepository;

        return view('store.main', $data);
    }
}

?>