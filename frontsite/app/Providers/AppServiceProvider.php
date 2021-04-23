<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CacheRepository;
use App\Repositories\ConfigRepository;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ConfigRepository $config)
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        view()->composer('errors::*', function($view) use ($config) {
            $view->with('config', $config->get());
        });
        $this->maintananceMode();
    }

    protected function maintananceMode()
    {
        $cache = new CacheRepository;
        $maintananceMode = config('constants.MAINTANANCE_MODE');
        if($maintananceMode) {
            $data = [];
            echo view('errors.503', $data);
            @exit;
        }
    }
}
