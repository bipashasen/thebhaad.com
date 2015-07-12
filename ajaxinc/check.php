<?php

if( !@$_SERVER["HTTP_X_REQUESTED_WITH"] ){
    header('HTTP/1.0 404 Forbidden');
    include '../pnp.php';
    exit;
}

?>