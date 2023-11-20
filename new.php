<?php
include 'data/db.php';
include 'data/session.php';
include 'template/engine.php';

$database = db_connect();

$session = new session($database);

if (!$session->is_logged_in()) {
    Header('Location: index.php');
    exit;
}

$content = <<<EOD


    <h3>making new document</h3>
    
    <form hx-post="./action/create-document.php" hx-target="#creation_result">
        <label>
            <p>title</p>
            <input class="field_input" placeholder="new document" name="writar_title" style="width: 20rem">
        </label>
        <label>
            <p>password</p>
            <input class="field_input" placeholder="leave empty if no password desired" name="writar_password" type="password" style="width: 20rem">
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

render_template("writing document", $content);