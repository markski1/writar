<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$document = get_document($database, $session, $_GET['id']);

$site = new Template("deleting document");
$site->set_description("");



if (!$document) {
    $site->render("<h3>error</h3><p>document does not exist.</p>");
    exit;
}

if (!$document->is_owner($session)) {
    $site->render("<h3>nuh uh</h3><p>you do not own this document.</p>");
    exit;
}

if (isset($_POST['confirm'])) {
    delete_document($database, $document->id);

    $site->render("<h3>document deleted.</h3><p><sitelink to=\"panel\">return to panel</sitelink></p>");
    exit;
}
else {
    $password_required_form = <<<EOD

        <h3>deleting document: {$document->title}</h3>
        
        <form hx-post hx-target="main">
            <p>please confirm this action</p>
            <input class="button" type="submit" value="delete this document" name="confirm">
        </form>

    EOD;

    $site->render($password_required_form);
    exit;
}