<?php
include_once './controllers/Users.php';


$user=new Users;
if(isset($_POST['add_user'])){
    print $user->save($_POST[user]);
}else{
    print $user->adduser_form();
}

?>
