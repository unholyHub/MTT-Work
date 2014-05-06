<?php
include_once './controllers/Reservations.php';
$reserve=new Reservations();
$subline=new Subline();
if(isset($_SESSION['buffer_id'])){
    if(isset($_POST['reserve'])){
        print '<div align="left" style="color:red;">'.$reserve->save($_POST['reserve']).'</div>';
    }
}
print $reserve->reserve_form($subline->find(array(id=>$_POST['reserve']['subline_id'])), $_POST['reserve']['date'],(isset($_POST['reserve']['date_back'])?1:null),$_POST['reserve']['date_back'], (isset($_POST['reserve']['back_subline_id'])?(int)$_POST['reserve']['back_subline_id']:null));

?>
