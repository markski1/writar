<?php

include_once '../data/session.php';
include_once '../data/db.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

$session->delete_account();

?>

<p>account deleted. all posts deleted. you have been logged out.</p>
