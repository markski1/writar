<?php
include_once '../data/session.php';

$session = new session;
?>

<h4>api key</h4>
<p>api key is used for api access. for more information about the api, <a href="/doc/D4Qjl" hx-get="/doc/D4Qjl" hx-target="main" hx-indicator='main'>click here</a>.</p>
<p>current key:</p>
<pre><?=$session->get_api_key()?></pre>
<p><a href="#" hx-get="../action/new-key.php" hx-target="#settings">generate new api key</a></p>