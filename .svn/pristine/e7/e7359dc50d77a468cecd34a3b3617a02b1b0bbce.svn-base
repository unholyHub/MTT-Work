<?php
include_once './controllers/Promotions.php';

$promo=new Promotions();
if(isset($_GET[did])){
    $promo->destroy($_GET[did]);
}
print $promo->lits_promotions();
?>
