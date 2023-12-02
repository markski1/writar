<?php

include_once '../data/session.php';
include_once '../data/db.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$random_string = '';

for ($i = 0; $i < 32; $i++) {
    $index = rand(0, strlen($characters) - 1);
    $random_string .= $characters[$index];
}

if ($session->change_api_key($random_string)) {
    echo "<p>your new api key:</p><pre>{$random_string}</pre>";
}
else {
    echo "<p>could not set a new api key.</p>";
}