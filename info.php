<?php
//phpinfo();
$urlServer = "http://192.168.1.26/";

$validation = "/^(http|https|ftp):\/\/((([1-9][0-9_-]*)\.([0-9][0-9_-]*)\.([0-9][0-9_-]*)\.([0-9][0-9_-]*))|([a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+))\/$/i";
if ((bool)preg_match($validation, $urlServer, $matches) === false) {
    echo '<pre>';
    print_r($matches);
    echo '<pre>';
    $urlServer =  "hack busted!";//_SERVER["REQUEST_SCHEME"] . "://" . _SERVER["HTTP_HOST"] . "/";
    echo $urlServer;
}


?>
