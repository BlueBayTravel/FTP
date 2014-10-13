<?php 

	namespace BlueBayTravel\FTP\Facades;

	use Illuminate\Support\Facades\Facade;

	class FTP extends Facade {
		/**
		 * Get the registered name of the component.
		 *
		 * @return string
		 */
		protected static function getFacadeAccessor() { return 'ftp'; }
	}