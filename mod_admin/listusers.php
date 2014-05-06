<?php
include_once './controllers/Users.php';
$users=new Users();
if(isset($_GET['del_id'])){
    $users->destroy($_GET['del_id']);    
}else{
    print $users->list_users();
}
?>
