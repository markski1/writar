<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$site = new Template("settings");

$content = <<<EOD

  
    <h3>settings</h3>
    
    <ul>
       <li><a href="#" hx-get="../components/change-password.php" hx-target="#settings">change password</a></li>
       <li><a href="#" hx-get="../action/delete-account.php" hx-target="main" hx-confirm="are you sure? all your documents will be deleted, this cannot be undone.">delete account</a></li>
       <li><a href="#" hx-get="../components/attributions.html" hx-target="#settings">third party attributions</a></li>
    </ul>
    
    <div id="settings" style="margin-bottom: 1rem;">
        <p>choose an option.</p>
    </div>
    
    <sitelink to="panel.php">return</sitelink>


EOD;

$site->render($content);