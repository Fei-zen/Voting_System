<?php

function slugify($string) {
    // Define common prepositions/articles to remove, if needed
    $preps = array('in', 'at', 'on', 'by', 'into', 'off', 'onto', 'from', 'to', 'with', 'a', 'an', 'the', 'using', 'for');
    $pattern = '/\b(?:' . join('|', $preps) . ')\b/i';

    // Remove prepositions and articles
    $string = preg_replace($pattern, '', $string);

    // Replace non-letter/digit characters with a hyphen
    $string = preg_replace('~[^\pL\d]+~u', '-', $string);

    // Transliterate to ASCII (if iconv is available and works correctly)
    if (function_exists('iconv')) {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    }

    // Remove any remaining invalid characters
    $string = preg_replace('~[^-\w]+~', '', $string);

    // Convert to lowercase
    $string = strtolower($string);

    // Trim hyphens from the beginning and end
    $string = trim($string, '-');

    return $string;
}

?>
