<?php
include_once './controllers/User_accesses.php';

$uai=new User_accesses();
$ua=new User_access();
if(isset($_POST[add_user_accesses])){
    $uai->save($_POST[user_access]);
}else{
    if(isset($_GET[user_id])){
        $uai=$ua->find(array(all=>1,where=>"user_id=".(int)$_GET[user_id]));
        if(isset($uai[0])){
            $uai=$uai[0];
            print $uai->user_access_form();
        }else{
            $uai=new User_accesses();
            $uai->user_id=(int)$_GET[user_id];
            print $uai->user_access_form();
        }
    }
}
?>
