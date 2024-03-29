<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$document = get_document($database, $session, $_GET['id']);

$site = new Template("editing document");

if (!$document) {
    $site->render("<p>document does not exist.</p>");
    exit;
}

if (!$document->is_owner($session)) {
    $site->render("<h3>nuh uh</h3><p>you do not own this document.</p>");
    exit;
}

$content = <<<HTML


    <h3>editing document</h3>
    
    <form hx-post="../action/create-document.php" hx-target="#creation_result">
        <label>
            <p>title</p>
            <input required class="field_input" autocomplete="off" placeholder="new document" value="{$document->get_title()}" name="writar_title" style="width: 20rem">
        </label>
        <label>
            <p>document<br><small class="light_text">use markdown for formatting. <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">learn more</a></small></p>
            <textarea required class="text_input" name="writar_document" placeholder="once upon a time...">{$document->get_content()}</textarea>
        </label>
        <p><small>please select privacy level again if document wasn't public.</small></p>
        <div style="margin-bottom: 2rem;">
            <label><input class="radiobtn" type="radio" name="privacy" onclick="showPassword(false)" value="public" checked>public document</label><br>
            <small class="light_text" style="margin-left: 1.8rem">can be seen by anyone, and may be indexed and discoverable through search functions.</small><br>
            <label><input class="radiobtn" type="radio" name="privacy" onclick="showPassword(false)" value="private">private document</label><br>
            <small class="light_text" style="margin-left: 1.8rem">will only be seen by people who you send the link to (unless you post the link somewhere public).</small><br>
            <label><input class="radiobtn" type="radio" name="privacy" onclick="showPassword(true)" value="password">passworded document</label><br>
            <small class="light_text" style="margin-left: 1.8rem">private, and will need a password to be viewed.</small><br>
            <input style="display: none; margin-top: 1rem; margin-left: 1.8rem" id="document_password" class="field_input" autocomplete="new-password" placeholder="password" name="writar_password" type="password" style="width: 20rem">
        </div>
        <input style="display:none" name="id" value="{$document->id}">
        <input class="button" name="update" type="submit" value="update document"> <input class="button" name="preview" type="submit" value="preview formatted document">
    </form>
    
    <div id="creation_result">
        
    </div>
    
    <script>
        function showPassword(option) {
            if (option) {
                document.getElementById('document_password').style.display = "block";
            }
            else {
                document.getElementById('document_password').style.display = "none";
            }
        }
    </script>


HTML;

$site = new Template("writing document");
$site->render($content);