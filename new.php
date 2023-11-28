<?php
include 'dependencies/init.php';
init($database, $session, "login_required");

$content = <<<EOD


    <h3>making new document</h3>
    
    <form hx-post="./action/create-document.php" hx-target="#creation_result">
        <label>
            <p>title</p>
            <input class="field_input" autocomplete="off" placeholder="new document" name="writar_title" style="width: 20rem">
        </label>
        <label>
            <p>password<!--<br /><small>used as an aes256 encryption passkey.</small>--!></p>
            <input class="field_input" autocomplete="new-password" placeholder="leave empty if no password desired" name="writar_password" type="password" style="width: 20rem">
        </label>
        <label>
            <p>document</p>
            <textarea class="text_input" name="writar_document" placeholder="once upon a time..."></textarea>
        </label>
        <br>
        
        <input class="button" name="create" type="submit" value="create document"> <input class="button" name="preview" type="submit" value="preview formatted document">
    </form>
    
    <div id="creation_result">
        
    </div>


EOD;

$site = new Template("writing document");
$site->render($content);