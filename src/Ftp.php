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

use Exception;

class Ftp
{
    /**
     * The active FTP connection resource id.
     */
    protected $connection;

    /**
     * Creates a new FTP connection instance.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->connection = $this->connect($config);
    }

    /**
     * Creates a new connection to an FTP server.
     *
     * @param array $config
     *
     * @throws \Exception
     *
     * @return resource
     */
    public function connect($config)
    {
        if (!isset($config['port'])) {
            $config['port'] = 21;
        }

        if (!isset($config['passive'])) {
            $config['passive'] = true;
        }

        $connection = ftp_connect($config['host'], $config['port']);
        $response = ftp_login($connection, $config['user'], $config['pass']);
        ftp_pasv($connection, $config['passive']);
        ftp_set_option($connection, FTP_TIMEOUT_SEC, 300);

        if (!$connection || !$response) {
            throw new Exception('FTP Connection Failed');
        }

        return $connection;
    }

    /**
     * Disconnects an active connection.
     *
     * @return bool
     */
    public function disconnect()
    {
        return ftp_close($this->connection);
    }

    /**
     * Returns a directory's file listing.
     *
     * @param string $directory
     *
     * @return array
     */
    public function ls($directory = '.')
    {
        return ftp_nlist($this->connection, $directory);
    }

    /**
     * Creates a new directory.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function dir($dir)
    {
        return ftp_mkdir($this->connection, $dir);
    }

    /**
     * Change into a directory.
     *
     * @param string $dir
     *
     * @return bool
     */
    public function chdir($dir)
    {
        return ftp_chdir($this->connection, $dir);
    }

    /**
     * Upload a local file to the server.
     *
     * @param string $fromFile
     * @param string $toFile
     *
     * @return bool
     */
    public function upload($fromFile, $toFile)
    {
        return ftp_put($this->connection, $toFile, $fromFile, $this->getTransferModeForFile($fromFile));
    }

    /**
     * Downloads a file from the server to a local destination.
     *
     * @param string $fromFile
     * @param string $toFile
     *
     * @return bool
     */
    public function download($fromFile, $toFile)
    {
        return ftp_get($this->connection, $toFile, $fromFile, $this->getTransferModeForFile($fromFile));
    }

    /**
     * Move up a directory.
     *
     * @return bool
     */
    public function up()
    {
        return ftp_cdup($this->connection);
    }

    /**
     * Chmod a file to the given permissions.
     *
     * @param string $mode
     * @param string $filename
     *
     * @return bool
     */
    public function chmod($mode, $filename)
    {
        return ftp_chmod($this->connection, $mode, $filename);
    }

    /**
     * Deletes a file path.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        return ftp_delete($this->connection, $path);
    }

    /**
     * Returns the current working directory.
     *
     * @return string
     */
    public function cwd()
    {
        return ftp_pwd($this->connection);
    }

    /**
     * Renames a path.
     *
     * @param string $oldPath
     * @param string $newPath
     *
     * @return bool
     */
    public function rename($oldPath, $newPath)
    {
        return ftp_rename($this->connection, $oldPath, $newPath);
    }

    /**
     * Deletes a directory.
     *
     * @param string $path
     *
     * @return bool
     */
    public function rmdir($path)
    {
        return ftp_rmdir($this->connection, $path);
    }

    /**
     * Returns the size of a path.
     *
     * @param string $path
     *
     * @return int
     */
    public function size($path)
    {
        return ftp_size($this->connection, $path);
    }

    /**
     * Returns the last modified time of a path.
     *
     * @param string $path
     *
     * @return int
     */
    public function mtime($path)
    {
        return ftp_mdtm($this->connection, $path);
    }

    /**
     * Returns the FTP_* type for a filename.
     *
     * @param string $filename
     *
     * @return int
     */
    public function getTransferModeForFile($filename)
    {
        $path = pathinfo($filename);

        if (isset($path['extension'])) {
            return $this->getTransferModeForExtension($path['extension']);
        } else {
            return FTP_ASCII;
        }
    }

    /**
     * Returns the FTP_* type by a given extension.
     *
     * @param string $extension
     *
     * @return int
     */
    public function getTransferModeForExtension($extension)
    {
        $extensions = ['am','asp','bat','c','cfm','cgi','conf','cpp','css','csv','dhtml','diz','h','hpp','htm','html','in','inc','js','m4','mak','nfs','nsi','pas','patch','php','php3','php4','php5','phtml','pl','po','py','qmail','sh','shtml','sql','tcl','tpl','txt','vbs','xml','xrc'];

        if (in_array(strtolower($extension), $extensions)) {
            return FTP_ASCII;
        } else {
            return FTP_BINARY;
        }
    }
}
