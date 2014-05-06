<?php
include_once './controllers/Reservations.php';
$res=new Reservations();
if(isset($_GET[drid])){
    $res->destroy((int)$_GET[drid]);
}
print $res->browse_list($_GET[subline],$_GET[_date],$_GET[passenger_name],$_GET[passenger_passport],$_GET[ticket_number]);
?>
