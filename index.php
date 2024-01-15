<?php
include 'dependencies/init.php';
init($database, $session);

if ($session->is_logged_in()) {
    Header('Location: panel');
    exit;
}

$content = <<<HTML


    <p>writar is a free text hosting and sharing service, currently in <sitelink to="doc/ioLN8">beta</sitelink>.</p>
    
    <p>features:</p>
    
    <ul>
       <li>markdown support</li>
       <li>up to 50,000 characters per document</li>
       <li>extremely lightweight</li>
       <li>document privacy levels: public, private, passworded</li>
       <li>no personal data stored</li>
       <li>no adverts, ever</li>
    </ul>
    
    <p>preview an <sitelink to="doc/tHKOk">example document</sitelink>.</p>
    
    <p>to publish and manage your documents, please identify.</p>
    
    <form hx-post="./action/identify.php" hx-target="#login_result">
        <input required class="field_input" placeholder="username" name="writar_username"> <br>
        <input required class="field_input" placeholder="password" type="password" name="writar_password"> <br>
        <input class="button" name="login" type="submit" value="login"> <input class="button" name="register" type="submit" value="make an account">
    </form>
    <p><small id="login_result"></small></p>


HTML;

$site = new Template("home");
$site->set_description("writar is a free, open source document sharing platform.");
$site->render($content);