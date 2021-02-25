<?php

namespace App\Helpers;

class Text {

    public static function excerpt(string $content,int $limit = 60): string
    {
        if (mb_strlen($content) <= 60){
            return $content;
        }
        $LastSpace = mb_strpos($content, ' ', $limit);
        return mb_substr($content, 0, $LastSpace) . '...';
    }
}