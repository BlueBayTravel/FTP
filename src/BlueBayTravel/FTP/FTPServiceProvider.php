<?php

	namespace BlueBayTravel\FTP;

	use Illuminate\Support\ServiceProvider;

	class FTPServiceProvider extends ServiceProvider {
		/**
		 * Indicates if loading of the provider is deferred.
		 * @var bool
		 */
		protected $defer = false;

		/**
		 * Bootstraps the application events.
		 * @return void
		 */
		public function boot() {
			$this->package('bluebaytravel/ftp');
		}

		/**
		 * Register the service provider.
		 * @return void
		 */
		public function register() {
			$this->app['ftp'] = $this->app->share(function($app) {
				return new FTPManager($app);
			});

			$this->app->booting(function() {
				$loader = \Illuminate\Foundation\AliasLoader::getInstance();
	            $loader->alias('FTP', 'BlueBayTravel\FTP\Facades\FTP');
			});
		}

		/**
		 * Get the services provided by the provider.
		 * @return array
		 */
		public function provides() {
			return array('ftp');
		}
	}
