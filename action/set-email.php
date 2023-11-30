<?php

include_once '../data/session.php';
include_once '../data/db.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

if ($session->change_email($_POST['writar_email'])) {
    echo "<p>email set.</p>";
}
else {
    echo "<p>could not set your e-mail.</p>";
}