<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

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