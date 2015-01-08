<?php

	return array(
		/*
		|--------------------------------------------------------------------------
		| Default FTP Connection
		|--------------------------------------------------------------------------
		|
		| Which connection should be used by default?
		| For added security, the connection settings below should be kept in an
		| environment variable and use getenv() to fetch the value.
		|
		*/
		'default' => 'server',

		/*
		|--------------------------------------------------------------------------
		| FTP Connections
		|--------------------------------------------------------------------------
		|
		| Store all FTP connections here.
		| By default FTP will use port 21.
		|
		*/
		'connections' => array(
			'server' => array(
				'host' => '127.0.0.1',
				'user' => 'admin',
				'pass' => 'pa55w0rd!',
				'port' => 21,
				'passive' => FALSE
			)
		)
	);
