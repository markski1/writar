<?php

include 'auth/auth.php';

function db_connect(): mysqli
{
    get_db_auth($db_host, $db_user, $db_password, $db_name, $cert_path);

    $mysqli = mysqli_init();
    $mysqli->ssl_set(NULL, NULL, $cert_path, NULL, NULL);
    if ($mysqli->real_connect($db_host, $db_user, $db_password, $db_name))
        return $mysqli;
    else
        exit("Cannot connect to database.");
}