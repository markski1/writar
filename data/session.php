<?php

class session {
    private mysqli $mysqli;
    private string $username;
    private int $id;
    private bool $isLoggedIn = false;

    function __construct($mysqli = null) {
        if ($mysqli == null) {
            include_once "db.php";
            $this->mysqli = db_connect();
        }
        else {
            $this->mysqli = $mysqli;
        }

        // both these cookies indicate a session is locally set.
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['session_token'])) {
            // if they exist, every user will have a session key in the database.
            $query = $this->mysqli->prepare("SELECT * FROM users WHERE session_token=? AND id=?");
            $query->bind_param("ss", $_COOKIE['session_token'], $_COOKIE['user_id']);
            $query->execute();

            $result = $query->get_result();

            // if there's a result, the user has a legitimate session.
            if ($result->num_rows > 0) {
                $result = $result->fetch_array();
                // within this session object, cache username, id and session status.
                $this->username = $result['username'];
                $this->id = $result['id'];
                $this->isLoggedIn = true;
            }
        }
    }

    function identify($username, $password): string
    {
        if (!ctype_alnum($username)) {
            return "username may only contain alphanumerics.";
        }

        if (strlen($password) > 72) {
            return "invalid password";
        }

        // check the key exists in the database.
        $query = $this->mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows < 1) {
            return "username or password is incorrect.";
        }

        $result = $result->fetch_array();

        if (!password_verify($password, $result['password'])) {
            return "username or password is incorrect.";
        }

        $user_id = $result['id'];

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';

        for ($i = 0; $i < 64; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $random_string .= $characters[$index];
        }

        // if everything checks out, set local session cookies in the user's browser.
        setcookie("user_id", $user_id, time() + (86400 * 30), "/");
        setcookie("session_token", $random_string, time() + (86400 * 30), "/");

        $query = $this->mysqli->prepare("UPDATE users SET session_token = ? WHERE id = ?");
        $query->bind_param("si", $random_string, $user_id);
        $query->execute();

        $this->username = $result['username'];
        $this->id = $user_id;
        $this->isLoggedIn = true;

        return "<script>window.location.replace('/panel');</script>";
    }

    function register($username, $password): string
    {
        if (!ctype_alnum($username)) {
            return "username may only contain alphanumerics.";
        }

        if (strlen($password) < 8) {
            return "password should be at least 8 characters long.";
        }

        if (strlen($password) > 72) {
            return "password can't be longer than 72 characters. no, this doesn't mean it's being stored in plaintext.";
        }

        $hashed_pword = password_hash($password, PASSWORD_BCRYPT);

        // check the user does not already exist.
        $query = $this->mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows > 0) {
            return "username is taken.";
        }

        $query = $this->mysqli->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
        $query->bind_param("ss", $username, $hashed_pword);
        $success = $query->execute();

        if (!$success) {
            return "sorry, could not create account.";
        }

        return 'account registered. you may now click "login".';
    }

    function change_password($current_password, $new_password): bool
    {
        $query = $this->mysqli->prepare("SELECT * FROM users WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows < 1) {
            return false;
        }

        $result = $result->fetch_array();

        if (!password_verify($current_password, $result['password'])) {
            var_dump($result['password']);
            return false;
        }

        $hashed_pword = password_hash($new_password, PASSWORD_BCRYPT);

        $query = $this->mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
        $query->bind_param("si", $hashed_pword, $this->id);
        $success = $query->execute();

        if (!$success) {
            return false;
        }

        return true;
    }

    function delete_account(): bool
    {
        $query = $this->mysqli->prepare("DELETE FROM users WHERE id = ?");
        $query->bind_param("i", $this->id);
        $success = $query->execute();

        if (!$success) return false;

        $query = $this->mysqli->prepare("DELETE FROM documents WHERE user_id = ?");
        $query->bind_param("i", $this->id);
        $success = $query->execute();

        if (!$success) return false;

        return true;
    }

    function is_logged_in(): bool
    {
        // set as a method in case this functionality changes,
        // but for now, the session constructor sets the following variable.
        return $this->isLoggedIn;
    }

    function logout(): void
    {
        // just remove all keys.
        setcookie("session_token", "_", time() - 3600, "/");
        setcookie("user_id", "_", time() - 3600, "/");
        // setcookie with negative time and 'unset' are redundant operations,
        // but, you know rule #2: double tap.
        unset($_COOKIE['session_token']);
        unset($_COOKIE['user_id']);
    }

    function get_username(): string
    {
        // set in the constructor.
        if (isset($this->username)) {
            return $this->username;
        }
        return "[invalid]";
    }

    function get_id(): int
    {
        // set in the constructor.
        if (isset($this->id)) {
            return $this->id;
        }
        return -1;
    }
}
?>