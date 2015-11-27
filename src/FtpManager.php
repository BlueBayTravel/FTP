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

use InvalidArgumentException;

class FtpManager
{
    /**
     * The config.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Create a new ftp manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->config['ftp.default'];
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();
        $connections = $this->config['ftp.connections'];

        if (is_null($config = array_get($connections, $name))) {
            throw new InvalidArgumentException("Ftp [{$name}] not configured.");
        }

        return $config;
    }

    /**
     * Create the Ftp connection instance.
     *
     * @param string $name
     *
     * @return \BlueBayTravel\Ftp\Ftp
     */
    protected function makeConnection($name)
    {
        $config = $this->getConfig($name);

        return new Ftp($config);
    }

    /**
     * Get a Ftp connection instance.
     *
     * @param string $name
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Disconnect from the given ftp.
     *
     * @param string $name
     *
     * @return void
     */
    public function disconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        if ($this->connections[$name]) {
            $this->connections[$name]->disconnect();
            unset($this->connections[$name]);
        }
    }

    /**
     * Reconnect to the given Ftp.
     *
     * @param string $name
     *
     * @return \BlueBayTravel\Ftp\Ftp
     */
    public function reconnect($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();
        $this->disconnect($name);

        return $this->connection($name);
    }

    /**
     * Return all of the created connections.
     *
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }
}
