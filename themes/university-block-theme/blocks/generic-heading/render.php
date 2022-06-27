<?php

switch($attributes['size']) {
    case "large":
        $tag = "h1";
        break;
    case "medium":
        $tag = "h2";
        break;
    case "small":
        $tag = "h3";
        break;
}

echo "<$tag class=\"headline headline--{$attributes['size']}\">{$attributes['text']}</$tag>";