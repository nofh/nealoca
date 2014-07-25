<?php 
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\BrowserConsoleHandler;

class Loggeur 
{
    public static $emp_general = 'general.log';
    public static $emp_dev= 'dev.log';
    public static $emp_debug= 'debug.log';
    public static $emp_performance= 'performance.log';
    public static $emp_db = 'db.log';

    private static $_log_general;
    private static $_log_dev;
    private static $_log_debug;
    private static $_log_performance;
    private static $_log_db;

    public static function init()
    {
    
        if( ! file_exists( self::$emp_general ) )
        {
            file_put_contents( self::$emp_general, '' );
        }
        self::$_log_general = new Logger( 'general' );
        self::$_log_general->pushHandler(new StreamHandler( self::$emp_general, Logger::WARNING));
    }

    // init loggeur
    public static function init_dev()
    {
        self::$_log_dev = new Logger( 'dev' );

        // browser
        $handler = new BrowserConsoleHandler();
        self::$_log_dev->pushHandler( $handler );

        // fichiers
        if( ! file_exists( self::$emp_dev ) )
        {
            file_put_contents( self::$emp_dev, '' );
        }

        $handler = new StreamHandler( self::$emp_dev, Logger::WARNING );
        self::$_log_dev->pushHandler( $handler );
        self::$_log_general->pushHandler( $handler );
    }

    public static function init_debug()
    {
        self::$_log_debug = new Logger( 'debug' );

        // browser
        $handler = new BrowserConsoleHandler();
        self::$_log_debug->pushHandler( $handler );

        // fichiers
        if( ! file_exists( self::$emp_debug ) )
        {
            file_put_contents( self::$emp_debug, '' );
        }

        $handler = new StreamHandler( self::$emp_debug, Logger::WARNING );
        self::$_log_dev->pushHandler( $handler );
        self::$_log_general->pushHandler( $handler );

    }

    public static function init_performance()
    {
        self::$_log_performance = new Logger( 'performance' );

        // fichiers
        if( ! file_exists( self::$emp_performance ) )
        {
            file_put_contents( self::$emp_performance, '' );
        }

        $handler = new StreamHandler( self::$emp_performance, Logger::WARNING );
        self::$_log_dev->pushHandler( $handler );
        self::$_log_general->pushHandler( $handler );
    }

    public static function init_db()
    {
        self::$_log_db = new Logger( 'db' );

        // fichiers
        if( ! file_exists( self::$emp_db ) )
        {
            file_put_contents( self::$emp_db, '' );
        }

        $handler = new StreamHandler( self::$emp_db, Logger::WARNING );
        self::$_log_dev->pushHandler( $handler );
        self::$_log_general->pushHandler( $handler );

    }

    // log
    public static function log_general( $message, $niveaux ) 
    {
        self::_log( self::$_log_general, $message, $niveaux );
    }

    public static function log_dev( $message, $niveaux )
    {
        self::_log( self::$_log_dev, $message, $niveaux );
    }

    public static function log_debug( $message, $niveaux )
    {
        self::_log( self::$_log_debug, $message, $niveaux );
    }

    public static function log_performance( $message, $niveaux )
    {
        self::_log( self::$_log_performance, $message, $niveaux );
    }

    public static function log_db( $message, $niveaux )
    {
        self::_log( self::$_log_db, $message, $niveaux );
    }

    // --
    private static function _log( $loggeur, $message, $niveaux )
    {
        if( ! empty( $loggeur ) )
        { 
            //$message = print_r( $message );
            foreach( $niveaux as $niveau )
            {
                switch( $niveau )
                {
                case 'debug':
                    echo " ecriture debug";
                    $loggeur->addDebug( $message );
                    break;
                 case 'info':
                    echo " ecriture info";
                    $loggeur->addInfo( $message );
                    break;
                 case 'notice':
                    echo " ecriture notice";
                    $loggeur->addNotice( $message );
                    break;
                 case 'notice':
                    echo " ecriture notice";
                    $loggeur->addNotice( $message );
                    break;
                 case 'warning':
                    echo " ecriture warning";
                    $loggeur->addWarning( $message );
                    break;
                 case 'error':
                    echo " ecriture error";
                    $loggeur->addError( $message );
                    break;
                case 'critical':
                    echo " ecriture critical";
                    $loggeur->addCritical( $message );
                    break;
                case 'alert':
                    echo " ecriture alert";
                    $loggeur->addAlert( $message );
                    break;
                case 'emergency':
                    echo " ecriture emergency";
                    $loggeur->addEmergency( $message );
                    break;
                default:
                    $loggeur->addInfo( $message );
                }
            }
        }
    }
}
?>
