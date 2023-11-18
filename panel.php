<?php
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    Header('Location: index.php');
}

$document_list = get_documents($database, $session->get_id());

$content = <<<EOD


    <h3>identified as {$session->get_username()}.</h3>
    
    <ul>
       <li>make new document</li>
       <li><a href="logout.php">logout</a></li>
    </ul>
    
    <h3>documents</h3>
    
    <ul>
        {$document_list}
    </ul>


EOD;

render_template('layout', "panel", $content);