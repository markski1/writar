<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$document_list = get_documents($database, $session->get_id());

$content = <<<EOD


    <h3>identified as {$session->get_username()}</h3>
    
    <ul>
       <li><sitelink to="new">new document</sitelink></li>
       <li><sitelink to="settings">settings</sitelink></li>
       <li><sitelink to="logout">logout</sitelink></li>
    </ul>
    
    <h3>documents</h3>
    
    <div>
        {$document_list}
    </div>


EOD;

$site = new Template("panel");
$site->render($content);