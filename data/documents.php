<?php

function get_documents($database, $user_id) {
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