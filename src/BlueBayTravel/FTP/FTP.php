<?php 

	namespace BlueBayTravel\FTP;

	use \Exception;

	class FTP {
		/**
		 * The active FTP connection resource id.
		 */
		protected $cID;

		/**
		 * Creates a new FTP connection instance.
		 * @param array $config
		 */
		public function __construct($config) {
			$this->cID = $this->connect($config);
		}

		/**
		 * Creates a new connection to an FTP server.
		 * @param array $config
		 * @return resource
		 * @throws \Exception
		 */
		public function connect($config) {
			if (!isset($config['port'])) {
				$config['port'] = 21;
			}

			if (!isset($config['passive'])) {
				$config['passive'] = TRUE;
			}

			$cID = ftp_connect($config['host'], $config['port']);
			$response = ftp_login($cID, $config['user'], $config['pass']);
			ftp_pasv($cID, $config['passive']);
			ftp_set_option($cID, FTP_TIMEOUT_SEC, 300);

			if (!$cID || !$response) {
				throw new Exception('FTP Connection Failed');
			}

			return $cID;
		}

		/**
		 * Disconnects an active connection.
		 * @return boolean
		 */
		public function disconnect() {
			return ftp_close($this->cID);
		}

		/**
		 * Returns a directory's file listing.
		 * @param  string $directory directory to list
		 * @return array
		 */
		public function listing($directory = '.') {
			return ftp_nlist($this->cID, $directory);
		}

		/**
		 * Creates a new directory
		 * @param  string $dir directory path to create
		 * @return boolean
		 */
		public function dir($dir) {
			return ftp_mkdir($this->cID, $dir);
		}

		/**
		 * Change into a directory
		 * @param string $dir directory to switch to
		 * @return boolean
		 */
		public function chdir($dir) {
			return ftp_chdir($this->cID, $dir);
		}

		/**
		 * Upload a local file to the server.
		 * @param  string $fromFile
		 * @param  string $toFile
		 * @return boolean
		 */
		public function upload($fromFile, $toFile) {
			return ftp_put($this->cID, $toFile, $fromFile, $this->getTransferModeForFile($fromFile));
		}

		/**
		 * Downloads a file from the server to a local destination.
		 * @param  string $fromFle
		 * @param  string $toFile
		 * @return boolean
		 */
		public function download($fromFle, $toFile) {
			return ftp_get($this->cID, $toFile, $fromFile, $this->getTransferModeForFile($fromFile));
		}

		/**
		 * Move up a directory.
		 * @return boolean
		 */
		public function up() {
			return ftp_cdup($this->cID);
		}

		/**
		 * Chmod a file to the given permissions.
		 * @param  string $mode
		 * @param  string $filename
		 * @return boolean
		 */
		public function chmod($mode, $filename) {
			return ftp_chmod($this->cID, $mode, $filename);
		}

		/**
		 * Deletes a file path.
		 * @param  string $path
		 * @return boolean
		 */
		public function delete($path) {
			return ftp_delete($this->cID, $path);
		}

		/**
		 * Returns the current working directory.
		 * @return string
		 */
		public function cwd() {
			return ftp_pwd($this->cID);
		}

		/**
		 * Renames a path.
		 * @param  string $oldPath
		 * @param  string $newPath
		 * @return boolean
		 */
		public function rename($oldPath, $newPath) {
			return ftp_rename($this->cID, $oldPath, $newPath);
		}

		/**
		 * Deletes a directory.
		 * @param  string $path
		 * @return boolean
		 */
		public function rmdir($path) {
			return ftp_rmdir($this->cID, $path);
		}

		/**
		 * Returns the size of a path.
		 * @param  string $path
		 * @return int
		 */
		public function size($path) {
			return ftp_size($this->cID, $path);
		}

		/**
		 * Returns the last modified time of a path.
		 * @param  string $path
		 * @return int
		 */
		public function mtime($path) {
			return ftp_mdtm($this->cID, $path);
		}

		/**
		 * Returns the FTP_* type for a filename.
		 * @param  string $filename
		 * @return int
		 */
		public function getTransferModeForFile($filename) {
			$path = pathinfo($filename);

			if (isset($path['extension'])) {
				return FTP_BINARY;
			} else {
				return $this->getTransferModeForExtension($path['extension']);
			}
		}

		/**
		 * Returns the FTP_* type by a given extension.
		 * @param  string $extension
		 * @return int
		 */
		public function getTransferModeForExtension($extension) {
			$extensions = array('am','asp','bat','c','cfm','cgi','conf','cpp','css','csv','dhtml','diz','h','hpp','htm','html','in','inc','js','m4','mak','nfs','nsi','pas','patch','php','php3','php4','php5','phtml','pl','po','py','qmail','sh','shtml','sql','tcl','tpl','txt','vbs','xml','xrc');

			if (in_array(strtolower($extension), $extensions)) {
				return FTP_ASCII;
			} else {
				return FTP_BINARY;
			}
		}
	}