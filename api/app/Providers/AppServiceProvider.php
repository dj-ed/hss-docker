<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Serializer\ArraySerializer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function () {
            $manager = new \League\Fractal\Manager;
            $manager->setSerializer(new ArraySerializer());
            return new \Dingo\Api\Transformer\Adapter\Fractal($manager, 'include', ',');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
