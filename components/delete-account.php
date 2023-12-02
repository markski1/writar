<form hx-post="../action/delete-account.php" hx-target="#settings">
    <h4>delete account</h4>
    <p>your account and every document you made will be permanently erased from the live db, and will disappear from every backup after ~14 days.</p>
    <small>the backup bit is only pointed out for transparency reasons.<br>i will not undo an account deletion, no matter who you are. this is permanent.</small><br>
    <input required class="field_input" placeholder="confirm password" type="password" name="writar_password"> <br>
    <input class="button" type="submit" value="permanently delete account">
</form>