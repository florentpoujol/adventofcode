<?php
$resource = fopen("04_input.txt", "r");

$validPhrases1 = 0;
$validPhrases2 = 0;

while (($line = fgets($resource)) !== false) {
    $line = trim($line);
    if ($line === "") {
        break;
    }

    $words = explode(" ", $line);
    if (array_unique($words) === $words) {
        $validPhrases1++;
    }


    $letters = array_map(function ($word) {
        $letters = str_split($word);
        sort($letters);
        return implode("", $letters);
    }, $words);

    if (array_unique($letters) === $letters) {
        $validPhrases2++;
    }
}

echo "Day 4.1: $validPhrases1 \n";
echo "Day 4.2: $validPhrases2 \n";