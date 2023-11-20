<?php

if (!@include '../dependencies/Parsedown.php') {
    include 'dependencies/Parsedown.php';
}

function get_documents($database, $user_id): string
{
    $query = $database->prepare("SELECT * FROM user_text WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "<li>no documents.</li>";
    }

    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $docs = "";
    foreach ($posts as $post) {
        $docs .= "<li><a href='view.php?id={$post['id']}'>{$post['title']}</a></li>";
    }

    return $docs;
}

function create_document($database, $title, $content, $password, $user_id): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    while (true) {
        $id = '';

        for ($i = 0; $i < 10; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $id .= $characters[$index];
        }

        $query = $database->prepare("SELECT id FROM user_text WHERE id = ?");
        $query->bind_param("s", $id);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows < 1) {
            break;
        }
    }

    if (strlen($title) == 0) {
        return "<p>please enter a title.</p>";
    }

    $content_length = strlen($content);

    if ($content_length < 10) {
        return "<p>a document should be at least 10 characters long.</p>";
    }

    if ($content_length > 50000) {
        $formatted_content_length = number_format($content_length);
        return "<p>a document may not be longer than 50,000 characters. currently is {$formatted_content_length}.</p>";
    }

    if (strlen($password) > 0) {
        if (strlen($password) > 72) {
            return "password can't be longer than 72 characters. no, this doesn't mean it's being stored in plaintext.";
        }
        $hashed_pword = password_hash($password, PASSWORD_BCRYPT);
    }
    else {
        $hashed_pword = '';
    }

    $query = $database->prepare("INSERT INTO user_text (title, content, user_id, id, password) VALUES(?, ?, ?, ?, ?)");
    $query->bind_param("ssiss", $title, $content, $user_id, $id, $hashed_pword);
    $success = $query->execute();

    if (!$success) {
        return "<p>sorry, could not create document.</p>";
    }

    return "<p>document created. <a href='view.php?id=$id'>go to document</a></p>";
}

function get_document($database, $session, $document_id): document | bool
{
    $query = $database->prepare("SELECT t.*, u.username FROM user_text as t INNER JOIN users as u ON t.user_id = u.id WHERE t.id = ?");
    $query->bind_param("s", $document_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return false;
    }

    return new document($session, $result->fetch_array());
}


class document
{
    private session $session;
    private string $id;
    private string $username;
    private string $title;
    private string $content;
    private string $created_at;
    private string $password;

    function __construct($session, $document_data)
    {
        $this->session = $session;
        $Parsedown = new Parsedown();

        $this->title = htmlspecialchars($document_data['title']);
        $this->content = htmlspecialchars($document_data['content']);

        if (strlen($this->title) == 0) $this->title = "untitled";
        if (strlen($this->content) == 0) $this->content = "document is empty.";

        $this->content = $Parsedown->text($this->content);

        $this->password = $document_data['password'];
        $this->username = $document_data['username'];
        $this->created_at = $document_data['created_at'];
        $this->id = $document_data['id'];
    }

    function needs_password(): bool
    {
        return strlen($this->password) != 0;
    }

    function check_password($password): bool
    {
        return password_verify($password, $this->password);
    }

    function render(): string
    {
        $render = "<h2>{$this->title}</h2>";

        $owner = false;

        if ($this->id != "PREVIEW_NOT_STORED") {
            if ($this->session->get_username() == $this->username) {
                $render .= "<p><small>written by <b>you</b> <span style='color: #777777'>at {$this->created_at}</span> | <a href='edit.php?id={$this->id}'>edit</a> | <a href='delete.php?id={$this->id}'>delete</a></small></p>";
                $owner = true;
            }
        }

        if (!$owner) {
            $render .= "<p><small>written by <b>{$this->username}</b> <span style='color: #777777'>at {$this->created_at}</span></small></p>";
        }

        $render .= "<hr><div class='document_content'>{$this->content}</div>";

        return $render;
    }
}