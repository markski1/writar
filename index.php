<?php
include 'dependencies/init.php';
init($database, $session);

if ($session->is_logged_in()) {
    Header('Location: panel');
    exit;
}

$content = <<<EOD


    <p>writar is a free text hosting and sharing service.</p>
    
    <ul>
       <li>markdown support</li>
       <li>up to 50,000 characters per document</li>
       <li>optional password protection</li>
       <li>extremely lightweight</li>
    </ul>
    
    <p>writar does not store or even accept personal data. only username and password.</p>
    
    <p>to publish and manage your documents, please identify.</p>
    
    <form hx-post="./action/identify.php" hx-target="#login_result">
        <input class="field_input" placeholder="username" name="writar_username"> <br>
        <input class="field_input" placeholder="password" type="password" name="writar_password"> <br>
        <input class="button" name="login" type="submit" value="login"> <input class="button" name="register" type="submit" value="make an account">
    </form>
    <p><small id="login_result"></small></p>


EOD;

$site = new Template("home");
$site->set_description("writar is a free, open source document sharing platform.");
$site->render($content);