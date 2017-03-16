<?php
namespace efabrikov\filetransfer;

class Logger
{
    private static $_instance;

    public static function getLogger()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new Logger();
        }

        return self::$_instance;
    }

    public static function log($message, $category = 'trace')
    {
        $backtrace = debug_backtrace();
        
        if (!empty($backtrace[1]['class'])) {
            $class =  $backtrace[1]['class'];
        }
        else {
            $class = get_called_class();
        }

        self::getLogger()->write($message, $class, $category = 'trace');
    }

    private function write($message, $class, $level)
    {
        $message = htmlspecialchars($message) . "<br>\n";
        
        print_r('[' . $level .'] ' . $class . ' ' . $message );
    }
}