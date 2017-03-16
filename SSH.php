<?php

namespace efabrikov\filetransfer;

use efabrikov\filetransfer\Logger;

class SSH
{
    public $connectionId;

    public $loginResult;


    public function __construct($user, $pass, $hostname, $umask = '0755')
    {
        Logger::log('__construct');
        $this->connect($hostname, $user, $pass);
    }

     public function connect($server, $sshUser, $sshPassword)
    {
        Logger::log('connect ');

        // *** Set up basic connection

 

        $this->connectionId = ssh2_connect($server, 22);

        if (empty($this->connectionId)) {
            Logger::log('ssh connection has failed!');
            throw new \Exception('ssh connection has failed!');
        }

        // *** Login with username and password
        $this->loginResult = ssh2_auth_password($this->connectionId, $sshUser, $sshPassword);

        if (empty($this->loginResult)) {
            Logger::log('ssh login has failed!');
            throw new \Exception('ssh login has failed!');
        }



        Logger::log('Connected to ' . $server . ', for user ' . $sshUser);
        $this->loginOk = true;
        return true;
    }

    public function cd($path)
    {
        Logger::log('cd ' . $path);

        return $path;
    }

    public function download($filename)
    {
        Logger::log('download ' . $filename);


        $result=ssh2_scp_recv($this->connectionId, $filename, $this->cd());

        return $result;
    }

    public function close()
    {
        Logger::log('close ');


        if (!empty($this->connectionId)) {
            ssh2_exec($this->connectionId, 'exit');
        }

        return true;
    }
}