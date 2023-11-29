<?php

include_once '../data/session.php';
include_once '../data/db.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

if ($session->change_password($_POST['writar_password'], $_POST['writar_password_new'])) {
    echo "<p>password changed.</p>";
}
else {
    echo "<p>current password is wrong.</p>";
}