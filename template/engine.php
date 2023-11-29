<?php


class Template {
    private string $title;
    private string $description;

    public function __construct(string $title) {
        $this->title = $title;
        $this->description = "";
    }

    function set_description(string $description): void
    {
        $this->description = $description;
    }

    function render(string $content): void
    {
        $sitelink_pattern = '/<sitelink to="([^"]*)">([^<]+)<\/sitelink>/';

        $content = preg_replace_callback(
            $sitelink_pattern,
            function ($matches) {
                $destination_url = $matches[1];
                $destination_text = $matches[2];

                return "<a href='../{$destination_url}' hx-get='../{$destination_url}' hx-push-url='true' hx-target='main'>{$destination_text}</a>";
            },
            $content
        );

        if (isset($_SERVER['HTTP_HX_REQUEST'])) {
            echo $content;
            echo "<script>
                      document.title = '{$this->title} - writar';
                  </script>";
            exit;
        }

        $site = file_get_contents("template/layout.html");

        // insert title and content into the template
        $site = str_replace("<!-- %%% SITE_TITLE %%% -->", $this->title, $site);
        $site = str_replace("<!-- %%% SITE_DESCRIPTION %%% -->", $this->description, $site);

        // define if darkmode and insert style sheets
        $style_header = '@import "../template/style.css";';
        if (!isset($_COOKIE['preferred_theme']) || $_COOKIE['preferred_theme'] == "dark") {
            $style_header .= '@import "../template/style_dark.css";';
        }
        $site = str_replace("<!-- %%% STYLE_SHEET %%% -->", $style_header, $site);

        $site = str_replace("<!-- %%% SITE_CONTENT %%% -->", $content, $site);

        echo $site;
    }
}