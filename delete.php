<?php
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    render_template("deleting document", "<p>document does not exist.</p>");
    exit;
}

if (!$document->is_owner($session)) {
    render_template("deleting document", "<p>you do not own this document.</p>");
    exit;
}

if (isset($_POST['confirm'])) {
    delete_document($database, $document->id);

    render_template("deleting document", "<p>document deleted.</p>");
}
else {
    $password_required_form = <<<EOD

        <h3>deleting document: {$document->title}</h3>
        
        <form method="POST">
            <p>please confirm this action</p>
            <input class="button" type="submit" value="delete this document" name="confirm">
        </form>

    EOD;

    render_template("deleting document", $password_required_form);
}