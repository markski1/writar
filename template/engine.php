<?php

function render_template($title, $content): void
{
    $template = file_get_contents("template/templates/layout.html");

    $site = str_replace("<!-- %%% SITE_TITLE %%% -->", $title, $template);
    $site = str_replace("<!-- %%% SITE_CONTENT %%% -->", $content, $site);

    echo $site;
}