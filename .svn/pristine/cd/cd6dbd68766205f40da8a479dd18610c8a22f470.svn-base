<?php
include_once './controllers/Reservations.php';
$reserve=new Reservations();
$subline=new Subline();
    if(isset($_POST['reserve'])){
        print '<div align="left" style="color:red;">'.$reserve->agent_save($_POST['reserve']).'</div>';
    }
print $reserve->agent_form($subline->find(array(id=>$_POST['reserve']['subline_id'])), $_POST['reserve']['date'],(isset($_POST['reserve']['date_back'])?1:null),$_POST['reserve']['date_back'], (isset($_POST['reserve']['back_subline_id'])?$_POST['reserve']['back_subline_id']:null), $_GET[opened_twoway]);

?>
