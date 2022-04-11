<?php

namespace Payment\System\App\Providers;

use Illuminate\Support\ServiceProvider as BestServiceProvider;
use Payment\System\App\Console\Commands\SyncBillingPlansCommand;
use Rebing\GraphQL\Support\Facades\GraphQL;


class ServiceProvider extends BestServiceProvider
{
    public function boot(){
        $this->publishes([
            __DIR__ . '/../../../config/app.php' =>config_path('paymentapp.php'),
        ]);
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncBillingPlansCommand::class
            ]);
        }
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/../../../config/app.php' ,'app');
        $this->mergeConfigFrom(__DIR__ . '/../../../config/graphql.php', 'graphql');
        $this->mergeConfigFrom(__DIR__ . '/../../../config/apidoc.php', 'apidoc');
        $this->mergeConfigFrom(__DIR__ . '/../../../config/services.php', 'services');

    }

}
