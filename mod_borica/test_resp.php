<?php
require 'Borica.php';
$bo = new Borica();
var_dump($bo->getResponse($_GET['eBorica']));
?>
