<?php

include 'auth/auth.php';

function db_connect(): mysqli
{
    get_db_auth($db_host, $db_user, $db_password, $db_name);

    return new mysqli($db_host, $db_user, $db_password, $db_name);
}