<?php
namespace efabrikov\filetransfer;

use efabrikov\filetransfer\Logger;
use efabrikov\filetransfer\FTP;
use efabrikov\filetransfer\SSH;

class Factory
{
    public function __construct()
    {
        Logger::log('construct');
    }

    public function getConnection($type, $user, $pass, $hostname, $umask = '0755')
    {
        Logger::log('getConnection');

        switch ($type)
        {
            case 'ssh':
                $obj = new  SSH($user, $pass, $hostname, $umask = '0755');
                break;
            default:
                $obj = new  FTP($user, $pass, $hostname, $umask = '0755');
                break;
        }

        return $obj;
    }
}
