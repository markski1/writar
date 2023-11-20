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
    $document_data['id'] = "PREVIEW_NOT_STORED";
    $document_data['title'] = $title;
    $document_data['content'] = $content;
    $document_data['password'] = "";
    $document_data['username'] = $session->get_username();
    $document_data['created_at'] = "0000-00-00 00:00:00";
    $document = new document($session, $document_data);
    echo $document->render();
    exit;
}

return "error in call.";