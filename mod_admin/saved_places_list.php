<?php
include_once './controllers/Saved_places.php';
$sp=new Saved_places();
if(isset($_GET[del_spid])){
    $sp->destroy($_GET[del_spid]);
}
print $sp->list_saved_places();
?>
