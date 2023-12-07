<?php

include 'auth/auth.php';

function db_connect(): mysqli
{
    get_db_auth($db_host, $db_user, $db_password, $db_name);

    $mysqli = mysqli_init();
    if ($mysqli->real_connect($db_host, $db_user, $db_password, $db_name))
        return $mysqli;
    else
        exit("Cannot connect to database.");
}