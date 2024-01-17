<?php
include "helpers.php";

$data = json_decode(file_get_contents('php://input'), true);

$err_msg = array(
    'success' => false,
    'message' => ''
);

if (!isset($_GET)) {
    error_out("no GET parameters received", 405);
}

if (!isset($_GET['id'])) {
    error_out("no document id provided", 400);
}

include '../data/db.php';
include '../data/session.php';
include '../data/documents.php';

$database = db_connect();
$session = new session($database);

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    error_out("document not found", 404);
}

if ($document->needs_password()) {
    if (!isset($_GET['password'])) {
        error_out("document needs password", 400);
    }
    else if (!$document->password_unlock($_GET['password'])) {
        error_out("password is incorrect", 403);
    }
}

$result = array(
    'success' => true,
    'title' => $document->get_title(),
    'content' => $document->get_content(),
    'author' => $document->get_author(),
    'created_at' => $document->created_at
);

echo json_encode($result);