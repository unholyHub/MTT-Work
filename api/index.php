<?php
require 'BTMLibApi.php';
$btmApi = new BTMLibApi('Hamburg', 'Plovdiv', 'bg','utf8');
$btmApi->loadDestinations();
?>
<pre>
<? var_dump($btmApi->data()) ?>
</pre>
