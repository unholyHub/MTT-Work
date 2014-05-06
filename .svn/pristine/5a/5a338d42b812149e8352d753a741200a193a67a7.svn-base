<?php
include_once './controllers/Promotions.php';
$promo=new Promotions();

if(isset($_POST['promotion_save'])){
    print $promo->save($_POST[promotion]);
}else{
    if(isset($_GET[id])){
        $p=new Promotion();
        $promo=$p->find(array(id=>(int)$_GET[id]));
        print $promo->promotion_form(null,true);
    }else{
        print $promo->promotion_form();
    }
}
?>
