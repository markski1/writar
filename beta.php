<?php
include 'dependencies/init.php';
init($database, $session);

$content = <<<EOD


    <h3>beta</h3>
    <p>writar is work in progress.</p>
    <p>roadmap:</p>
    <ul>
        <li>document editing</li>
        <li>standalone domain</li>
        <li>ux improvements</li>
        <li>user profiles (disabled by default - if enabled, will list your public documents)</li>
        <li>end to end document encryption</li>
    </ul>


EOD;

$site = new Template("beta information");
$site->render($content);