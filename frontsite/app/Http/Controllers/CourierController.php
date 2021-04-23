<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Helpers\FunctionsHelper;
use App\Repositories\CustomerRepository;
use App\Repositories\ConfigRepository;
use App\Repositories\RajaOngkirRepository;
use DB;

class CourierController extends BaseController
{

	protected $_config;
	protected $_func;
	
	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_config = $configs; 
		$this->_func = $functions;
    }

    public function getCost(Request $request, RajaOngkirRepository $rajaongkir)
    {
        $origin = $request->origin; 
        $originType = $request->originType;
        $destination = $request->destination;
        $destinationType = $request->destinationType;
        $weight = $request->weight;
        $courier = $request->courier;
        $result = $rajaongkir->getCost($origin, $originType, $destination, $destinationType, $weight, $courier);
        
        if(isset($result['rajaongkir'])) 
        {
            if(isset($result['rajaongkir']['status']) && ($result['rajaongkir']['status']['code'] == 200))
            {
                if(isset($result['rajaongkir']['results']))
                {
                    $data = [];
                    foreach($result['rajaongkir']['results'] as $res)
                    {
                        if(sizeof($res['costs']) > 0) 
                        {
                            foreach($res['costs'] as $costs)
                            {   
                                $cost = reset($costs['cost']);
                                $costValue = $cost['value'];
                                $costEtd = $cost['etd'];
                                $data[] = [
                                    'service' => $costs['service'],
                                    'description' => $costs['description'],
                                    'cost'=>$costValue,
                                    'etd'=>$costEtd
                                ];
                            }
                            return [
                                'status'=>'success',
                                'data'=>$data
                            ];
                        }
                    }
                }
            }
        }
        return [
            'status'=>'failed',
            'data'=>[]
        ];
    }

    public function getTracking(Request $request, RajaOngkirRepository $rajaongkir)
    {
        $waybill = $request->waybill;
        $courier = $request->courier;
        $result = $rajaongkir->waybill($waybill, $courier);
        if(isset($result['rajaongkir'])) 
        {
            if(isset($result['rajaongkir']['status']) && ($result['rajaongkir']['status']['code'] == 200))
            {
                return [
                    'status'=>'success',
                    'message'=>$result['rajaongkir']['status']['description'],
                    'data'=>$result['rajaongkir']['result']
                ];
            } else {
                return [
                    'status'=>'failed',
                    'message'=>$result['rajaongkir']['status']['description'],
                    'data'=>[]
                ];
            }
        } else {
            return [
                'status'=>'failed',
                'message'=>'Sistem tidak mengenali status pengiriman',
                'data'=>[]
            ];
            
        }
        return $result;
    }
}


