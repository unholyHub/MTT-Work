<?php
include_once './controllers/Active_places.php';

$act=new Active_place();
if(isset($_POST[add_inactive])){
    $active_pls=new Active_places();
    $active_pls->save($_POST[active_places]);
}else{
    $active_pls=$act->find(array(all=>'',where=>'line_id='.(int)$_GET[lid]));
    if(isset($active_pls[0])) $active_pls=$active_pls[0];
    else $active_pls=new Active_places();
    
    print $active_pls->active_place_form((int)$_GET[lid]);
}

?>
