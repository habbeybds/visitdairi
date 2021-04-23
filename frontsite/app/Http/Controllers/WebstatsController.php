<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Repositories\ConfigRepository;
use App\Repositories\CustomerRepository;
use App\Helpers\FunctionsHelper;
use App\Models\Webstats;
use DB;

class WebstatsController extends BaseController
{

	protected $_func;
	protected $_config;
	protected $_ignoreIps = [
		'0.0.0.0',
		'127.0.0.1'
	];

	public function __construct(FunctionsHelper $functions, ConfigRepository $configs)
	{
		$this->_func = $functions;
		$this->_config = $configs;
	}

	public function index(Request $request)
	{

		// generate user key
		$userKey = $request->session()->get('user_activity_key');
		if(!$userKey) {
			$userKey = Uuid::uuid4();
			session(['user_activity_key' => $userKey]);
		}

		// get current path
		$currpath = $request->post('currpath');
		if(!$currpath) {
			$currpath = '/';
		}
		$currpath = str_replace(url('/'), '', $currpath);

		// get referer
		$referer = $request->post('referer');
		if(!$referer) {
			$referer = '';
		}

		// get user agent
		$useragent = $request->header('user-agent');
		$device = 'desktop';
		if(preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $useragent))
		{
			$device = 'mobile';
		}

		$ip = $request->ip();
		if(!in_array($ip, $this->_ignoreIps)) 
		{
			$datetime = date('Y-m-d H:i:s');
			$webstat = Webstats::where('id', $userKey)
				->where('ip', $ip)
				->where('pagevisit', $currpath)
				->where('logdate', date('Y-m-d'))
				->first();

			if($webstat) {
				$webstat->update([
					'pagehit' => DB::raw('pagehit + 1'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			} else {
				$webstat = Webstats::insert([
					'id' => $userKey,
					'logdate' => date('Y-m-d'),
					'ip' => $ip,
					'pagehit' => 1,
					'useragent' => $useragent,
					'device' => $device,
					'pagevisit' => $currpath,
					'referer' => $referer,
					'created_at' => $datetime,
					'updated_at' => $datetime
				]);
			}
		}
		return false;
	}


	// Returns server load in percent (just number, without percent sign)
    public function getServerLoad()
    {
        $load = null;

        if (stristr(PHP_OS, "win"))
        {
            $cmd = "wmic cpu get loadpercentage /all";
            @exec($cmd, $output);

            if ($output)
            {
                foreach ($output as $line)
                {
                    if ($line && preg_match("/^[0-9]+\$/", $line))
                    {
                        $load = $line;
                        break;
                    }
                }
            }
        } else {
			$load = sys_getloadavg();
		}

        return $load;
    }

	public function webstattest()
	{
		// $date = date('Y-m-d');
		// $webstats = DB::select('SELECT * FROM '.DB::getTablePrefix().'webstats WHERE logdate=?', [$date]);
		// $totalHit = 0;
		// $keys = [];
		// $pageVisit = [];
		// foreach($webstats as $web)
		// {
		// 	$keys[$web->id][] = count($webstats->toArray()[$web->id]);
		// 	$pageHit = (int)$web->pagehit;
		// 	$totalHit += $pageHit;
		// 	$page = trim($web->pagevisit);
		// 	if(isset($pageVisit[$page])) {
		// 		$pageVisit[$page] += $pageHit;
		// 	} else {
		// 		$pageVisit[$page] = $pageHit;
		// 	}
		// }

		// arsort($pageVisit);
		// $countValue = array_unique($keys);


		// return [
		// 	'count_value' => $countValue,
		// 	'visitor' => sizeof($keys),
		// 	'page_hit' => $totalHit,
		// 	'page_visit' => $pageVisit
		// ];
	}


}