<?php

/*
 * This file is part of Ftp.
 *
 * (c) Blue Bay Travel <developers@bluebaytravel.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default
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
    | Connections
    |--------------------------------------------------------------------------
    |
    | Store all FTP connections here.
    |
    | By default FTP will use port 21.
    |
    */

    'connections' => [
        'server' => [
            'host'    => '127.0.0.1',
            'user'    => 'admin',
            'pass'    => 'pa55w0rd!',
            'port'    => 21,
            'passive' => false,
        ],
    ],
];
