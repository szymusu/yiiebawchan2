<?php

namespace common\helpers;

class TextFormat
{
    /**
     * @param $text string
     * @return string
     */
    public static function paragraphs($text)
    {
        $text = "<p class='mb-2'>$text</p>";
        str_replace("\n", "</p>\n<p class='mb-2'>", $text);
        return $text;
    }
}