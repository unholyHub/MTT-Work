<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Borica
 *
 * @author alexander
 */
require_once __DIR__.'/config.php';
class Borica {
    
    public function __construct() {}
    
    public function checkSign($data, $signature) {
        $fp = fopen(Config::$publicKey, "r");
        $cert = fread($fp, 8192);
        fclose($fp);
        $pubkeyid = openssl_get_publickey($cert);
        
        $ok = openssl_verify($data, $signature, $pubkeyid);
        
        openssl_free_key($pubkeyid);
        
        return $ok;
    }
    
    public function sign(&$message) {
        $fp = fopen(Config::$privateKey, "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, Config::$password);
        
        openssl_sign($message, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        $message .= $signature;
    }
    
    public function createRequest($orderID, $description, $amount, $language) {
        $message = Config::TRANSACTION_CODE;
        $message .= date("YmdHis", mktime());
        $message .= str_pad(100*$amount, 12, "0", STR_PAD_LEFT);
        $message .= Config::TERMINAL_ID;
        $message .= str_pad($orderID, 15);
        $message .= str_pad($description, 125);
        $message .= $language;
        $message .= Config::PROTOCOL_VERSION;
        
        $this->sign($message);
        
        if (Config::TRANSACTION_CODE == '10' && Config::STATUS_FLAG == "0") {
            $action = "registerTransaction?eBorica=";
        } else {
            if (Config::TRANSACTION_CODE == '10' && Config::STATUS_FLAG == "1") {
                $action = "transactionStatusReport?eBorica=";
            } else {
                $action = "manageTransaction?eBorica=";
            }
        }
        
        $request = array(
            'data' => $message,
            'url' => Config::$gate . $action . urlencode(base64_encode($message))
        );
        
        return $request;
    }
    
    public function getResponse($message) {
        // manipulation of the $_GET["eBorica"] parameter
        $message = base64_decode($message);
        
        $response['TRANSACTION_CODE'] = substr($message, 0, 2);
        $response['TRANSACTION_TIME'] = substr($message, 2, 14);
        $response['AMOUNT'] = substr($message, 16, 12);
        $response['TERMINAL_ID'] = substr($message, 28, 8);
        $response['ORDER_ID'] = substr($message, 36, 15);
        $response['RESPONSE_CODE'] = substr($message, 51, 2);
        $response['PROTOCOL_VERSION'] = substr($message, 53, 3);
        $response['SIGN'] = substr($message, 56, 128);
        $response['SIGNOK'] = $this->checkSign(substr($message, 0, strlen($message) - 128), $response['SIGN']);

        return $response;
    }
}

?>
