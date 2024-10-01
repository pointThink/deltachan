<?php
header("Content-type: text/css");

// this will be loaded from cookies later
$css_file_name = "deltachan.css";

$base_style_file = fopen(__DIR__ . "/base_style.css", "r");
$base_style = fread($base_style_file, filesize(__DIR__ . "/base_style.css")) . "\n";

$css_file = fopen(__DIR__ . "/styles/" . $css_file_name, "r");
$css = fread($css_file, filesize(__DIR__ . "/styles/" . $css_file_name));

echo $base_style . $css;
