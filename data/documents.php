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
        $docs .= "<li><a href='view.php?id={$post['url_id']}'>{$post['title']}</a></li>";
    }

    return $docs;
}

function get_document($database, $document_id): string
{
    $query = $database->prepare("SELECT t.*, u.username FROM user_text as t INNER JOIN users as u ON t.user_id = u.id WHERE url_id = ?");
    $query->bind_param("s", $document_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "document not found.";
    }

    $result = $result->fetch_array();

    return render_document($result['title'], $result['content'], $result['username'], $result['created_at']);
}

function create_document($database, $title, $content, $password, $user_id): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    while (true) {
        $url_id = '';

        for ($i = 0; $i < 10; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $url_id .= $characters[$index];
        }

        $query = $database->prepare("SELECT id FROM user_text WHERE url_id = ?");
        $query->bind_param("s", $url_id);
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
        $hashed_pword = password_hash($password, PASSWORD_BCRYPT);
    }
    else {
        $hashed_pword = '';
    }

    $query = $database->prepare("INSERT INTO user_text (title, content, user_id, url_id, password) VALUES(?, ?, ?, ?, ?)");
    $query->bind_param("ssiss", $title, $content, $user_id, $url_id, $hashed_pword);
    $success = $query->execute();

    if (!$success) {
        return "<p>sorry, could not create document.</p>";
    }

    return "<p>document created. <a href='view.php?id=$url_id'>go to document</a></p>";
}

function render_document($title, $content, $username, $datetime): string
{
    if (strlen($title) == 0) $title = "untitled";
    if (strlen($content) == 0) $content = "document is empty.";

    $title = htmlspecialchars($title);
    $content = htmlspecialchars($content);

    $Parsedown = new Parsedown();

    $content = $Parsedown->text($content);

    $render = "<h2>{$title}</h2>";

    $render .= "<p><small>written by {$username} <span style='color: #777777'>at {$datetime}</span></small></p> <hr>";

    $render .= "<div class='document_content'>{$content}</div>";

    return $render;
}