<?php
App::uses('Errbit', 'Lib/vo-errbit-cake/errbit/lib');

class ErrbitCakePHP extends ErrorHandler{
    public static $settings;
    private $errbit;

    function __construct(){
        $this->errbit = Errbit::instance();
        $this->errbit->configure(array(
            'api_key'           => self::$settings['api_key'],
            'host'              => self::$settings['host'],
            'port'              => self::$settings['port'],
            'secure'            => false,
            'project_root'      => '/',
            'environment_name'  => self::$settings['environment_name'],
            'params_filters'    => array('/password/', '/card_number/'),
            'backtrace_filters' => array('#/some/long/path#' => '')
        ));
    }

    public static function handleError($code, $description, $file = null, $line = null, $context = null) {
        $errbitCake = new ErrbitCakePHP();
        $errbitCake->onError($code, $description, $file, $line);
        return parent::handleError($code, $description, $file, $line, $context);
    }

    public static function handleException(Exception $exception) {
        $errbitCake = new ErrbitCakePHP();
        $errbitCake->onException($exception);
        return parent::handleException($exception);
    }

    public function onError($code, $message, $file, $line) {
        $sendNotify = true;
        switch ($code) {
            case E_USER_NOTICE:
            case E_NOTICE:
                $exception = new Errbit_Errors_Notice($message, $file, $line, debug_backtrace());
                break;

            case E_WARNING:
            case E_USER_WARNING:
                if( !self::$settings['showWarnigns'] ){
                    $sendNotify = false;
                    break;
                }
                $exception = new Errbit_Errors_Warning($message, $file, $line, debug_backtrace());
                break;

            case E_ERROR:
            case E_USER_ERROR:
            default:
                if( !self::$settings['showErrors'] ){
                    $sendNotify = false;
                    break;
                }
                $exception = new Errbit_Errors_Error($message, $file, $line, debug_backtrace());
        }

        if( $sendNotify ){
            $this->errbit->notify($exception);
        }
    }

    public function onException($exception) {
        $this->errbit->notify($exception);
    }
}
