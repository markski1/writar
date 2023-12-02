<?php

include_once '../data/session.php';

$session = new session;

$username = $_POST['writar_username'] ?? '??INVALID??';
$password = $_POST['writar_password'] ?? '??INVALID??';

if (isset($_POST['login'])) {
    $result = $session->identify($username, $password);
    if ($result['success']) {
        Header('HX-Location: {"path":"/panel", "target":"main"}');
    }
    else {
        echo $result['message'];
    }
    exit;
}

if (isset($_POST['register'])) {
    $result = $session->register($username, $password);
    exit;
}

return "error in call.";