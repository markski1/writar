<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    render_template("deleting document", "<h3>error</h3><p>document does not exist.</p>");
    exit;
}

if (!$document->is_owner($session)) {
    render_template("deleting document", "<h3>nuh uh</h3><p>you do not own this document.</p>");
    exit;
}

if (isset($_POST['confirm'])) {
    delete_document($database, $document->id);

    render_template("deleting document", "<h3>document deleted.</h3><p><sitelink to=\"panel.php\">return to panel</sitelink></p>");
}
else {
    $password_required_form = <<<EOD

        <h3>deleting document: {$document->title}</h3>
        
        <form hx-post hx-target="main">
            <p>please confirm this action</p>
            <input class="button" type="submit" value="delete this document" name="confirm">
        </form>

    EOD;

    render_template("deleting document", $password_required_form);
}