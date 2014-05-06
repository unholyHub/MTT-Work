<?php
/* 
 * 
 */
include_once './controllers/Session.php';

$login=new Session();
if(isset($_GET[logout])){
    $login->destroy_session();
}elseif(isset($_POST['log_user'])){
    print $login->create_session($_POST[login]);
}else{
    print $login->login_form();
}
?>
