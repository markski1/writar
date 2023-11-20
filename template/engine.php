<?php

function render_template($title, $content): void
{
    $template = file_get_contents("template/layout.html");

    $site = str_replace("<!-- %%% SITE_TITLE %%% -->", $title, $template);
    $site = str_replace("<!-- %%% SITE_CONTENT %%% -->", $content, $site);

    $style_header = '@import "template/style.css";';

    if (isset($_COOKIE['preferred_theme']) && $_COOKIE['preferred_theme'] == "dark") {
        $style_header .= '@import "template/style_dark.css";';
    }

    $site = str_replace("<!-- %%% STYLE_SHEET %%% -->", $style_header, $site);
    $site = str_replace("<!-- %%% CURRENT_URL %%% -->", $_SERVER['REQUEST_URI'], $site);

    echo $site;
}