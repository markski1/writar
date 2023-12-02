<?php
Header('Cache-Control: private, max-age=300');
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

function init(&$database, &$session, $condition = ""): void
{
    $database = db_connect();
    $session = new session($database);

    if ($condition == "login_required") {
        if (!$session->is_logged_in()) {
            Header('Location: ../');
            exit;
        }
    }
}
