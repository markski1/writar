<?php
include 'data/db.php';
include 'data/session.php';
include 'data/documents.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    render_template("not found", "<p>document does not exist.</p>");
    exit;
}

if ($document->needs_password()) {
    if (isset($_POST['writar_document_password'])) {
        if (!$document->check_password($_POST['writar_document_password'])) {
            render_template("password required", "<p>Wrong password.</p>");
            exit;
        }
    }
    else {
        $password_required_form = <<<EOD

            <h3>a password is required to view this document</h3>
            
            <form hx-post hx-target="main">
                <input class="field_input" placeholder="enter document password" type="password" autocomplete="new-password" name="writar_document_password"> <br>
                <input class="button" type="submit" value="view document">
            </form>

        EOD;

        render_template("password required", $password_required_form);
        exit;
    }
}

render_template($document->title, $document->render());