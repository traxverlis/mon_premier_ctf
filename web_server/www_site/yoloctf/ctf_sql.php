<?php
    //$mysqli = new mysqli("webserver_mysql", "root", "AZ56FG78HJZE34", "dbctf");
    $mysqli = new mysqli("webserver_mysql", "ctfuser", "passwordforctfuser", "dbctf");
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

?>