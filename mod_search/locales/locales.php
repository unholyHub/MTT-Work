<?php
/* 
 * 
 */
class locales {
    static $current_locale;
    static $text;

    static function create_locale(){
        if(isset($_GET['lang'])){
            if(isset($_SESSION['locale'])){
                locales::$current_locale=$_GET['lang'];
                $_SESSION['locale']=$_GET['lang'];
            }else{
                
                locales::$current_locale=$_GET['lang'];
                $_SESSION['locale']=$_GET['lang'];
            }
        }else{
            if(isset($_SESSION['locale'])){
                locales::$current_locale=$_SESSION['locale'];
            }else{
                locales::$current_locale='bg';
                $_SESSION['locale']='bg';
            }
        }
    }

    static function reset_locale(){
        locales::$current_locale='bg';
        $_SESSION['locale']='bg';
    }
}
locales::create_locale();

if(!file_exists('./locales/'.locales::$current_locale.'.php')) locales::reset_locale();
if($_SERVER['PHP_SELF']=='/reservations/payment_recv.php'){
    include_once './locales/en.php';
}else{
    include_once './locales/'.locales::$current_locale.'.php';
}
?>
