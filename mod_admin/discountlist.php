<?php
include_once './controllers/Discounts.php';

$discounts=new Discounts();
if(isset($_GET[del_id])){
    $discounts->destroy((int)$_GET[del_id]);
}else{
    print $discounts->list_discounts();
}
?>
