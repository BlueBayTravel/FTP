<?php

/*
 * This file is part of Ftp.
 *
 * (c) Blue Bay Travel <developers@bluebaytravel.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BlueBayTravel\Ftp;

use Illuminate\Support\ServiceProvider;

class FtpServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/ftp.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('ftp.php')]);
        }

        $this->mergeConfigFrom($source, 'ftp');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFtp();
    }

    /**
     * Register the ftp class.
     *
     * @return void
     */
    protected function registerFtp()
    {
        $this->app->singleton('ftp', function ($app) {
            return new FtpManager($app->config);
        });

        $this->app->alias('ftp', FtpManager::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ftp'];
    }
}
