<?php
include_once './controllers/Reservations.php';
$reserve=new Reservations();
$reserveHandler = new Reservation();
$subline=new Subline();
    if(isset($_POST['reserve'])){
        print '<div align="left" style="color:red;">'.$reserve->edit_agent_save($_POST['reserve']).'</div>';
    }
    $opened_twoway = ($_POST['reserve']['ticket_type']==2?true:false);
    $reservation = $reserveHandler->find(array(id=>(int)$_POST['reserve']['id']));
    print $reservation->agent_edit_form($subline->find(array(id=>$_POST['reserve']['subline_id'])), $_POST['reserve']['date'], (isset($_POST['reserve']['date_back'])?1:null), (isset($_POST['reserve']['back_subline_id'])?$subline->find(array(id=>$_POST['reserve']['back_subline_id'])):null), $_POST['reserve']['date_back'], null, $opened_twoway)

?>
