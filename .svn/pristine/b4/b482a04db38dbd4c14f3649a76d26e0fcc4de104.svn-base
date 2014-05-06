<?php
include_once './controllers/Reservations.php';
if(isset($_GET[_subline])){
    $reserve=new Reservations();
    $subln=new Sublines();
    $subline=$subln->getSubline((int)$_GET['_subline'], $_GET['_date']);
    if(isset($_GET[back])){
        $subline_back = $subline->getBackSubline($_GET['_date_back']);
        print '<div class="head">'.locales::$text['tickets_back'].'</div>'.$reserve->bus_places($subline_back, $_GET['_date_back'],'_back');
    }else{
        print '<div class="head">'.locales::$text['tickets_going'].'</div>'.$reserve->bus_places($subline, $_GET['_date']);
    }
}
?>
