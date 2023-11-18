<?php

function render_template($template_name, $title, $content): void
{
    $template = file_get_contents("template/templates/{$template_name}.html");

    $site = str_replace("<!-- %%% SITE_TITLE %%% -->", $title, $template);
    $site = str_replace("<!-- %%% SITE_CONTENT %%% -->", $content, $site);

    echo $site;
}