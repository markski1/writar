<?php

include_once '../data/session.php';
include '../data/documents.php';

$session = new session;

$title = $_POST['writar_title'] ?? '??INVALID??';
$content = $_POST['writar_document'] ?? '??INVALID??';

if (isset($_POST['create'])) {
    echo create_document($title, $content);
    exit;
}

if (isset($_POST['preview'])) {
    echo render_document($title, $content);
    exit;
}

return "error in call.";