<?php
include 'dependencies/init.php';
init($database, $session);

$content = <<<EOD


    <h3>terms of use</h3>
    
    <p>the following types of document will not be allowed:</p>
    <ul>
        <li>links to illegal material</li>
        <li>doxxing / revealing of personal data without a subject's authorization</li>
        <li>anything that would land me, in status of site operator, in hot legal water</li>
    </ul>
    <p>otherwise i really don't care.</p>
    
    <p>regarding privacy - no "identifying" details (ip address or forms of digital fingerprinting) are linked to user accounts.</p>
    
    <p>ip addresses are temporarily stored in a database table - not linked to user data - for ratelimiting/abuse prevention purposes. this table is often pruned.</p>


EOD;

$site = new Template("terms and privacy");
$site->render($content);