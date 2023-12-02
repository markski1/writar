<?php

include_once '../data/session.php';
include_once '../data/db.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

if ($session->delete_account($_POST['writar_password'])) {
    echo '<p>account deleted. all posts deleted. you have been logged out.</p>';
}
else {
    echo '<p>account was not deleted, there was an error. is the password correct?</p>';
}

?>


