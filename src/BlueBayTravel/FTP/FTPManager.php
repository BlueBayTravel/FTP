<?php 

	namespace BlueBayTravel\FTP;

	class FTPManager {
		/**
		 * The application instance.
		 *
		 * @var \Illuminate\Foundation\Application
		 */
		protected $app;

		/**
		 * The active connection instances.
		 *
		 * @var array
		 */
		protected $connections = array();

		/**
		 * Create a new FTP instance.
		 *
		 * @param  \Illuminate\Foundation\Application  $app
		 * @return void
		 */
		public function __construct($app) {
			$this->app = $app;
		}

		/**
		 * Get the default connection name.
		 *
		 * @return string
		 */
		public function getDefaultConnection() {
			return $this->app['config']['ftp::default'];
		}

		/**
		 * Get the configuration for a connection.
		 *
		 * @param  string  $name
		 * @return array
		 *
		 * @throws \InvalidArgumentException
		 */
		protected function getConfig($name) {
			$name = $name ?: $this->getDefaultConnection();
			$connections = $this->app['config']['ftp::connections'];

			if (is_null($config = array_get($connections, $name))) {
				throw new \InvalidArgumentException("FTP [$name] not configured.");
			}

			return $config;
		}

		/**
		 * Create the FTP connection instance.
		 *
		 * @param  string  $name
		 * @return \BlueBayTravel\FTP\FTP
		 */
		protected function makeConnection($name) {
			$config = $this->getConfig($name);
			return new FTP($config);
		}

		/**
		 * Get a FTP connection instance.
		 *
		 * @param  string  $name
		 */
		public function connection($name = null) {
			$name = $name ?: $this->getDefaultConnection();

			if (!isset($this->connections[$name])) {
				$this->connections[$name] = $this->makeConnection($name);
			}

			return $this->connections[$name];
		}

		/**
		 * Disconnect from the given ftp.
		 *
		 * @param  string  $name
		 * @return void
		 */
		public function disconnect($name = null) {
			$name = $name ?: $this->getDefaultConnection();

			if ($this->connections[$name]) {
				$this->connections[$name]->disconnect();
				unset($this->connections[$name]);
			}
		}

		/**
		 * Reconnect to the given FTP.
		 *
		 * @param  string  $name
		 * @return \BlueBayTravel\FTP\FTP
		 */
		public function reconnect($name = null) {
			$name = $name ?: $this->getDefaultConnection();
			$this->disconnect($name);
			return $this->connection($name);
		}

		/**
		 * Return all of the created connections.
		 *
		 * @return array
		 */
		public function getConnections() {
			return $this->connections;
		}
	}