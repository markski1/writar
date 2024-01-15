<?php

if (!@include '../dependencies/Parsedown.php') {
    include 'dependencies/Parsedown.php';
}

function get_documents($database, $user_id): string
{
    $query = $database->prepare("SELECT id, title, visits FROM documents WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "<p>no documents.</p>";
    }

    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $docs = "";
    foreach ($posts as $post) {
        $docs .= /* @lang HTML */
            <<<HTML
                
            <div class="listed_post">
                <h3>{$post['title']}</h3>
                <p><sitelink to="doc/{$post['id']}">view</sitelink> - <sitelink to="edit/{$post['id']}">edit</sitelink> - <sitelink to="delete/{$post['id']}">delete</sitelink><br>    
                <span class="light_text">{$post['visits']} visits.</span></p>
            </div>

            HTML;
    }

    return $docs;
}

function create_document($database, $id, $title, $content, $password, $user_id, $privacy): array
{
    if (strlen($title) == 0) {
        return array(
            'success' => false,
            'message' => 'please enter a title.'
        );
    }

    $content_length = strlen($content);

    if ($content_length < 10) {
        return array(
            'success' => false,
            'message' => 'a document should be at least 10 characters long.'
        );
    }

    if ($content_length > 50000) {
        $formatted_content_length = number_format($content_length);
        return array(
            'success' => false,
            'message' => "a document may not be longer than 50,000 characters. currently is {$formatted_content_length}."
        );
    }

    if ($privacy == 2) {
        if (strlen($password) > 0) {
            if (strlen($password) > 72) {
                return array(
                    'success' => false,
                    'message' => "password can't be longer than 72 characters."
                );
            }
            // hash the plaintext password to bcrypt
            $hashed_pword = password_hash($password, PASSWORD_BCRYPT);
        } else {
            return array(
                'success' => false,
                'message' => "enter a password, or change privacy level."
            );
        }
    } else {
        $hashed_pword = '';
    }

    // if $id is null, we're creating a document, otherwise we're updating.
    if ($id == null) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        while (true) {
            $id = '';

            for ($i = 0; $i < 5; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $id .= $characters[$index];
            }

            $query = $database->prepare("SELECT id FROM documents WHERE id = ?");
            $query->bind_param("s", $id);
            $query->execute();

            $result = $query->get_result();

            if ($result->num_rows < 1) {
                break;
            }
        }

        $query = $database->prepare("INSERT INTO documents (title, content, user_id, id, password, privacy) VALUES(?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssissi", $title, $content, $user_id, $id, $hashed_pword, $privacy);
        $success = $query->execute();

        if (!$success) {
            return array(
                'success' => false,
                'message' => "document could not be created."
            );
        }
        return array(
            'success' => true,
            'message' => "document created.",
            'id' => $id
        );
    }
    else {
        $query = $database->prepare("SELECT user_id FROM documents WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows < 1) {
            return array(
                'success' => false,
                'message' => "document could not be edited. please try again in a few seconds."
            );
        }

        $result = $result->fetch_array();

        if ($result['user_id'] != $user_id) {
            return array(
                'success' => false,
                'message' => "you don't own the document"
            );
        }

        $query = $database->prepare("UPDATE documents SET title = ?, content = ?, password = ?, privacy = ? WHERE id = ?");
        $query->bind_param("sssis", $title, $content, $hashed_pword, $privacy, $id);
        $success = $query->execute();

        if ($success) {
            return array(
                'success' => true,
                'message' => "document edited.",
                'id' => $id
            );
        }
        return array(
            'success' => false,
            'message' => "document could not be edited. please try again in a few seconds."
        );
    }
}

function get_document($database, $session, $document_id): document | bool
{
    $query = $database->prepare("SELECT t.*, u.username FROM documents as t INNER JOIN users as u ON t.user_id = u.id WHERE t.id = ?");
    $query->bind_param("s", $document_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return false;
    }

    return new document($database, $session, $result->fetch_array());
}

function delete_document($database, $document_id): void
{
    $query = $database->prepare("DELETE FROM documents WHERE id = ?");
    $query->bind_param("s", $document_id);
    $query->execute();
}


class document
{
    private session $session;
    private mysqli $database;
    public string $id;
    public string $author;
    public string $title;
    public string $content;
    public string $created_at;
    private string $password;

    function __construct($database, $session, $document_data)
    {
        $this->session = $session;
        $this->database = $database;

        $this->title = htmlspecialchars($document_data['title']);
        $this->content = $document_data['content'];

        if (strlen($this->title) == 0) $this->title = "untitled";
        if (strlen($this->content) == 0) $this->content = "document is empty.";

        $this->password = $document_data['password'] ?? '';
        $this->author = $document_data['username'] ?? 'unknown';
        $this->created_at = $document_data['created_at'] ?? 'unknown';
        $this->id = $document_data['id'];
    }

    function needs_password(): bool
    {
        return strlen($this->password) != 0;
    }

    function password_unlock($entered_password): bool
    {
        if (password_verify($entered_password, $this->password)) {
            // eventually do encryption
            return true;
        }
        return false;
    }

    function is_owner($session): bool
    {
        return $session->get_username() == $this->author;
    }

    function render(): string
    {
        $render = "<h2>{$this->title}</h2>";

        $owner = false;

        // "PREVIEW_NOT_STORED" as the id means this is a preview in the document creator.
        if ($this->id != "PREVIEW_NOT_STORED") {
            $query = $this->database->prepare("UPDATE documents SET visits = visits + 1 WHERE id = ?");
            $query->bind_param("s", $this->id);
            $query->execute();
            if ($this->session->get_username() == $this->author) {
                $render .= "<p><small>written by <b>you</b> <span class='light_text'>at {$this->created_at}</span> | <sitelink to=\"edit/{$this->id}\">edit</sitelink> - <sitelink to=\"delete/{$this->id}\">delete</sitelink></small></p>";
                $owner = true;
            }
        }

        if (!$owner) {
            $render .= "<p><small>written by <b>{$this->author}</b> <span class='light_text'>at {$this->created_at}</span></small></p>";
        }

        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $render .= "<hr><div class='document_content'>{$Parsedown->text($this->content)}</div>";

        return $render;
    }
}