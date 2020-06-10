<?php
namespace App\Helpers;

class Text {

    public static function excerpt(string $content, int $limit = 60)
    {
        // strlen recuperer la taille du la chaine
        if(mb_strlen($content) <= $limit) {
            return $content;
        }
        // position d'une chaine du caractere dans une chaine du caractere
        $lastSpace = mb_strpos($content,' ',$limit);
        // chaine du caractere trop grande
        return mb_substr($content, 0,$lastSpace) . '...';
    }


}
