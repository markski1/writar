<?php
$style_header = '@import "template/style.css";';

if (!isset($_COOKIE['preferred_theme']) || $_COOKIE['preferred_theme'] == "dark") {
    setcookie("preferred_theme", "light", time()+(84600*30), "/");
    $_COOKIE['preferred_theme'] = "light";
}
else {
    setcookie("preferred_theme", "dark", time()+(84600*30), "/");
    $_COOKIE['preferred_theme'] = "dark";
    $style_header .= '@import "template/style_dark.css";';
}

echo $style_header;