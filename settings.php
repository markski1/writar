<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$site = new Template("settings");

$content = <<<HTML

  
    <h3>settings</h3>
    
    <ul>
       <li><a href="#" hx-get="../components/change-password.php" hx-target="#settings">change password</a></li>
       <li><a href="#" hx-get="../components/set-email.php" hx-target="#settings">set e-mail</a></li>
       <li><a href="#" hx-get="../components/api-key.php" hx-target="#settings">api key</a></li>
       <li><a href="#" hx-get="../components/delete-account.php" hx-target="#settings">delete account</a></li>
       <li><a href="#" hx-get="../components/attributions.html" hx-target="#settings">third party attributions</a></li>
    </ul>
    
    <div id="settings" style="margin-bottom: 1rem; border: 1px black solid; padding: 0 1rem 1rem">
    </div>
    
    <sitelink to="panel">return</sitelink>


HTML;

$site->render($content);