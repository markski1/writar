<?php
    include 'data/db.php';
    include 'template/engine.php';

    $content = <<<EOD


    <p>writar is a free text sharing and hosting service.</p>
    
    <ul>
       <li>markdown support</li>
       <li>up to 50,000 characters per document</li>
       <li>optional password protection</li>
       <li>extremely lightweight</li>
    </ul>
    
    <p>writar does not store (or even accept) personal data. only username and password.</p>
    
    <p>to publish and manage your documents, please identify.</p>
    

    <form>
        <input class="field_input" placeholder="username" name="writar_username"> <br>
        <input class="field_input" placeholder="password" type="password" name="writar_password"> <br>
        <input class="button" name="login" type="submit" value="login"> <input class="button" name="register" type="submit" value="make an account">
    </form>
    <p><small id="login_result">awaiting action.</small></p>


    EOD;
    render_template('layout', "home", $content);