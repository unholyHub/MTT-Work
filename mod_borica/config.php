<?php
class Config {
    const TRANSACTION_CODE = '10';
    const STATUS_FLAG = '0';
    const PROTOCOL_VERSION = '1.0';
    const TERMINAL_ID = '15510323';
    
    public static $certificate;
    public static $privateKey;
    public static $publicKey;
    public static $password;
    public static $gate;
}
/* DEVELOPMENT ENVIROMENT */
//Config::$certificate = __DIR__.'/certs/test/test_t_f.cer';
//Config::$privateKey = __DIR__.'/certs/test/test.key';
//Config::$publicKey = __DIR__.'/certs/test/Borika_public_key_test.key';
//Config::$gate = 'https://gatet.borica.bg/boreps/';

/* PRODUCTION ENVIROMENT */
Config::$certificate = __DIR__.'/certs/APGW/APGW_p_f.cer';
Config::$privateKey = __DIR__.'/certs/APGW/APGW.key';
Config::$publicKey = __DIR__.'/certs/APGW/Borika_public_key.key';
Config::$gate = 'https://gate.borica.bg/boreps/';

Config::$password = '';
?>
