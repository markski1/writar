<?php
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    Header('Location: index.php');
    exit;
}

$document_list = get_documents($database, $session->get_id());

$content = <<<EOD


    <h3>identified as {$session->get_username()}</h3>
    
    <ul>
       <li><sitelink to="new.php">new document</sitelink></li>
       <li><sitelink to="settings.php">settings</sitelink></li>
       <li><sitelink to="logout.php">logout</sitelink></li>
    </ul>
    
    <h3>documents</h3>
    
    <ul>
        {$document_list}
    </ul>


EOD;

render_template("panel", $content);