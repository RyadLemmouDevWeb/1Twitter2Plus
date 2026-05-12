<?php

function format_content($content)
{
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    $content = preg_replace(
        '/@(\w+)/',
        '<a href="/account?username=$1" class="text-teal-600 hover:underline font-medium">@$1</a>',
        $content
    );

    $content = preg_replace(
        '/#(\w+)/',
        '<a href="/search?q=%23$1" class="text-orange-500 hover:underline font-medium">#$1</a>',
        $content
    );

    return $content;
}
