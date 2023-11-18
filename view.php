<?php
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

$document = get_document($database, $_GET['id']);

render_template('layout', "viewing document", $document);