<?php
include_once '../data/session.php';

$session = new session;
?>

<form hx-post="../action/set-email.php" hx-target="#settings">
    <h4>set e-mail</h4>
    <p>not needed. only used if you require support or a new password (you'll have to e-mail me from it)</p>
    <p>current email:</p>
    <pre><?=$session->get_email()?></pre>
    <input type="email" required class="field_input" placeholder="e-mail address" name="writar_email">
    <input class="button" type="submit" value="set e-mail">
</form>