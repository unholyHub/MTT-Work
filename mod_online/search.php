<?php
include_once './controllers/Lines.php';
include_once './controllers/Cities.php';
$lines = new Lines();

print $lines->search_serialized();
?>
