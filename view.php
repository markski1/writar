<?php
include 'dependencies/init.php';
init($database, $session);

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    render_template("not found", "<p>document does not exist.</p>");
    exit;
}

if ($document->needs_password()) {
    if (isset($_POST['writar_document_password'])) {
        if (!$document->password_unlock($_POST['writar_document_password'])) {
            render_template("password required", "<h3>access denied</h3><p>wrong password.</p>");
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