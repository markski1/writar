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
    foreach ($posts as &$post) {
        $docs .= "<li><a href='view.php?id={$post['url_id']}'>{$post['title']}</a></li>";
    }

    return $docs;
}

function get_document($database, $document_id): string
{
    $query = $database->prepare("SELECT * FROM user_text WHERE url_id = ?");
    $query->bind_param("i", $document_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "document not found.";
    }

    $result = $result->fetch_array();

    return render_document($result['title'], $result['content']);
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


    $query = $database->prepare("INSERT INTO user_text (title, content, user_id, url_id, password) VALUES(?, ?, ?, ?, ?)");
    $query->bind_param("ssiss", $title, $content, $user_id, $url_id, $password);
    $success = $query->execute();

    if (!$success) {
        return "<p>sorry, could not create document.</p>";
    }

    return "<p>document created. <a href='view.php?id=$url_id'>go to document</a></p>";
}

function render_document($title, $content): string
{
    if (strlen($title) == 0) $title = "untitled";
    if (strlen($content) == 0) $content = "document is empty.";

    $title = htmlspecialchars($title);
    $content = htmlspecialchars($content);

    $Parsedown = new Parsedown();

    $content = $Parsedown->text($content);

    $render = "<h2>{$title}</h2>";

    $render .= "<div>{$content}</div>";

    return $render;
}