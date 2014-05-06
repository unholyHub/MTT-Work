<?php
include_once './controllers/Saved_places.php';
$saved_pls=new Saved_places();
$sp=new Saved_place();
if(isset($_POST[add_saved_places])){
    print $saved_pls->save($_POST[saved_places]);
}elseif(isset($_GET[spid])){
    $saved_pls=$sp->find(array(id=>(int)$_GET[spid]));
    print $saved_pls->saved_places_form();
}else{
    print $saved_pls->saved_places_form();
}

?>
