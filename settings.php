<?php
include 'template/engine.php';
include 'data/db.php';
include 'data/session.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    Header('Location: index.php');
    exit;
}

$content = <<<EOD

  
    <h3>settings</h3>
    
    <ul>
       <li><a href="#">change username</a></li>
       <li><a href="#">change password</a></li>
       <li><a href="#">delete account</a></li>
    </ul>
    
    <sitelink to="panel.php">return</sitelink>


EOD;

render_template("panel", $content);