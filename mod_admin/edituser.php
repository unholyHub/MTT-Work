<?php
include_once './controllers/Users.php';

$user=new User();
$users=new Users();
if(isset($_POST[update_user])){
    print $users->update($_POST[user]);
}elseif(isset($_GET[id])){
    $users=$user->find(array(id=>(int)$_GET[id]));
    print $users->edit_user();
}
?>
