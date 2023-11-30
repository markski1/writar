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
$privacy_str = $_POST['privacy'] ?? 'private';

$privacy = match ($privacy_str) {
    "password" => 2,
    "private" => 1,
    default => 0,
};

if (isset($_POST['preview'])) {
    $document_data['id'] = "PREVIEW_NOT_STORED";
    $document_data['title'] = $title;
    $document_data['content'] = $content;
    $document_data['password'] = "";
    $document_data['username'] = $session->get_username();
    $document_data['created_at'] = (new DateTime())->format('Y-m-d H:i:s');
    $document = new document($database, $session, $document_data);
    echo $document->render();
    exit;
}

if (!$session->can_do_operation(30)) {
    echo "<p>can't do that yet, please try in a few seconds.</p>";
    exit;
}

if (isset($_POST['create'])) {
    echo create_document($database, null, $title, $content, $password, $session->get_id(), $privacy);
    $session->update_last_operation();
    exit;
}

if (isset($_POST['update']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    echo create_document($database, $id, $title, $content, $password, $session->get_id(), $privacy);
    $session->update_last_operation();
    exit;
}

return "error in call.";