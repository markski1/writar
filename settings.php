<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$content = <<<EOD

  
    <h3>settings</h3>
    
    <ul>
       <li><a href="#">change password</a></li>
       <li><a href="#">delete account</a></li>
    </ul>
    
    <sitelink to="panel.php">return</sitelink>


EOD;

$site = new Template("settings");
$site->render($content);