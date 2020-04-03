<?php
require_once 'HTMLPurifier.auto.php';

$dirty_html = $_GET['html'];

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$clean_html = $purifier->purify($dirty_html);
?>

<h4>clean:</h4>
<pre><?php echo $clean_html ?></pre>
