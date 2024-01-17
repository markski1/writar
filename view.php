<?php
include 'dependencies/init.php';
init($database, $session);

$document = get_document($database, $session, $_GET['id']);

if (!$document) {
    $site = new Template("not found");
    $site->set_description("this document does not exist");
    $site->render("<p>document does not exist.</p>");
    exit;
}

if ($document->needs_password()) {
    if (isset($_POST['writar_document_password'])) {
        if (!$document->password_unlock($_POST['writar_document_password'])) {
            $site = new Template("wrong password");
            $site->render("<h3>access denied</h3><p>wrong password.</p>");
            exit;
        }
    }
    else {
        $password_required_form = <<<HTML

            <h3>a password is required to view this document</h3>
            
            <form hx-post hx-target="main">
                <input class="field_input" placeholder="enter document password" type="password" autocomplete="new-password" name="writar_document_password"> <br>
                <input class="button" type="submit" value="view document">
            </form>

        HTML;

        $site = new Template("passworded document");
        $site->set_description("this document requires a password");
        $site->render($password_required_form);
        exit;
    }
}

$site = new Template($document->get_title());
$site->set_description("a document by {$document->get_author()}");
$site->render($document->render());