<?php
include "helpers.php";

try {
    $data = @json_decode(file_get_contents('php://input'), true);
    if (!$data) throw new Exception('not json');
}
catch (exception) {
    error_out("no json params received", 405);
}

if (!isset($data['api_key'])) {
    error_out('no api key provided', 401);
}

if (!isset($data['title'])) {
    error_out('no title provided', 400);
}

if (!isset($data['content'])) {
    error_out('no document content provided', 400);
}

include '../data/db.php';
include '../data/session.php';
include '../data/documents.php';

$database = db_connect();
$session = new session($database, $data['api_key']);

if (!$session->is_logged_in()) {
    error_out('api key is invalid', 401);
}

echo json_encode(create_document(
    database: $database,
    id: null,
    title: $data['title'],
    content: $data['content'],
    password: '',
    user_id: $session->get_id(),
    privacy: 1
));