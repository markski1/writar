<?php

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

function create_document($title, $content): string
{
    return "<p>not yet implemented.</p>";
}

function render_document($title, $content): string
{
    $render = "<h2>{$title}</h2>";

    $render .= "<div>{$content}</div>";

    return $render;
}