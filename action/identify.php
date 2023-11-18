<?php

include_once '../data/session.php';

$session = new session;

$username = $_POST['writar_username'] ?? '??INVALID??';
$password = $_POST['writar_password'] ?? '??INVALID??';

if (isset($_POST['login'])) {
    echo $session->identify($username, $password);
    exit;
}

if (isset($_POST['register'])) {
    echo $session->register($username, $password);
    exit;
}

return "error in call.";