<?php
//phpinfo();
$urlServer = $_GET['x'];

$validation = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
if ((bool)preg_match($validation, $urlServer) === false) {
    $urlServer =  "hack busted!";//_SERVER["REQUEST_SCHEME"] . "://" . _SERVER["HTTP_HOST"] . "/";
    echo $urlServer;
}


?>
