<?php

// Analyze furigana by Mecab
function get_furigana(string $text) {
    exec("echo " . $text . " | mecab", $res);
    $furigana = "";
    foreach ($res as $line) {
        if ($line === "EOS")
            break;
        $cells = explode("\t", $line);
        if (isset($cells[1])) {
            $details = explode(',', $cells[1]);
            $readKatakana = $details[7] ?? '';
            $furigana .= $readKatakana;
        }
    }
    return $furigana;
}
