<?php
include_once './controllers/Discounts.php';

$discounts=new Discounts();
if(isset($_POST['add_discount'])){
    print $discounts->save($_POST[discount]);
}else{
    if(isset($_GET[id])) print $discounts->add_discount_form('',$_GET[id]);
    else print $discounts->add_discount_form();
}
?>
