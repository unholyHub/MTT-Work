<?php
include_once './controllers/Reports.php';

$report=new Reports();
print $report->free_places_report($_GET['date_from'], $_GET['date_to'], (int)$_GET['subline']);
?>
