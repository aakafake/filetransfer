<?php

namespace efabrikov\filetransfer;

use efabrikov\filetransfer\Logger;

class FTP
{
    public $connectionId;

    public function __construct($user, $pass, $hostname, $umask = '0755')
    {
        Logger::log('__construct');
        $this->connect($hostname, $user, $pass);
    }

    public function connect($server, $ftpUser, $ftpPassword, $isPassive = false)
    {
        Logger::log('connect ');

        // *** Set up basic connection
        $this->connectionId = ftp_connect($server);

        if (empty($this->connectionId)) {
            Logger::log('FTP connection has failed!');
            throw new \Exception('FTP connection has failed!');
        }

        // *** Login with username and password
        $loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);

        if (empty($loginResult)) {
            Logger::log('FTP login has failed!');
            throw new \Exception('FTP login has failed!');
        }

        // *** Sets passive mode on/off (default off)
        ftp_pasv($this->connectionId, $isPassive);

        Logger::log('Connected to ' . $server . ', for user ' . $ftpUser);
        $this->loginOk = true;
        return true;
    }

    public function pwd()
    {
        Logger::log('pwd ');
        $result = ftp_pwd($this->connectionId);

        if (empty($result)) {
            Logger::log('pwd command return false');
        }

        return $result;
    }

    public function upload($filename)
    {
        Logger::log('upload ' . $filename);

        $this->_uploadFile($filename, $filename);

        return $this;
    }

    private function _uploadFile($fileFrom, $fileTo)
    {
        Logger::log('_uploadFile ' . $fileFrom . ' ' . $fileTo);

        // *** Set the transfer mode
        $asciiArray = array('txt', 'csv');
        $extension  = array_reverse(explode('.', $fileFrom))[0];

        Logger::log($extension);
        //$last_key = key( array_slice( $array, -1, 1, TRUE ) );
        if (in_array($extension, $asciiArray)) {
            $mode = FTP_ASCII;
        } else {
            $mode = FTP_BINARY;
        }

        // *** Upload the file
        Logger::log('ftp_put ' . $fileTo . ' ' . $fileFrom);

        $upload = ftp_put($this->connectionId, $fileTo, $fileFrom, $mode);

        // *** Check upload status
        if (empty($upload)) {
            Logger::log('FTP upload has failed!');
            throw new \Exception('FTP upload has failed!');
        }

        Logger::log('Uploaded "' . $fileFrom . '" as "' . $fileTo);

        return true;
    }

    public function exec($command)
    {
        Logger::log('exec ' . $command);        
        $result = ftp_exec($this->connectionId, $command);

        if (empty($result)) {
            Logger::log('exec command return false');
        }
        return $result;
    }

    public function close()
    {
        if (!empty($this->connectionId)) {
            ftp_close($this->connectionId);
        }

        return true;
    }
}