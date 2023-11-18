<?php

include_once '../data/session.php';
include_once '../data/db.php';
include '../data/documents.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    return "auth error.";
}

$title = $_POST['writar_title'] ?? '??INVALID??';
$content = $_POST['writar_document'] ?? '??INVALID??';
$password = $_POST['writar_password'] ?? '';


if (isset($_POST['create'])) {
    echo create_document($database, $title, $content, $password, $session->get_id());
    exit;
}

if (isset($_POST['preview'])) {
    echo render_document($title, $content, $session->get_username(), '0000-00-00 00:00:00');
    exit;
}

return "error in call.";