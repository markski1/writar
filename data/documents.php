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
    $query = $database->prepare("SELECT * FROM user_text WHERE id = ?");
    $query->bind_param("i", $document_id);
    $query->execute();

    $result = $query->get_result();

    if ($result->num_rows < 1) {
        return "document not found.";
    }

    $result = $result->fetch_array();

    return render_document($result['title'], $result['content']);
}

function create_document($title, $content): string
{
    return "<p>not yet implemented.</p>";
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