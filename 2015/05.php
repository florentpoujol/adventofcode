<?php
// http://adventofcode.com/2015/day/5

$resource = fopen("05_input.txt", "r");

$niceCount = 0;
$niceCount2 = 0;

function hasLetterTwiceInARow($str) 
{
    $count = strlen($str) - 1;
    for ($i = 0; $i < $count; $i++) {
        if ($str[$i] === $str[$i+1]) {
            return true;
        }
    }
    return false;
}

function hasPairs($str, $v = false)
{
    $count = strlen($str) - 1;
    for ($i = 0; $i < $count; $i++) {
        $chunk = $str[$i] . $str[$i+1];
        
        $pos = strpos(substr($str, $i+2), $chunk);
        if ($pos !== false) {
            return $v ? $pos : true;
        }
    }
    return false;
}

function hasRepeatingLetterWithAnotherInBetween($str) 
{
    $count = strlen($str) - 2;
    for ($i = 0; $i < $count; $i++) {
        if ($str[$i] === $str[$i+2]) {
            return true;
        }
    }
    return false;
}

while (($line = fgets($resource)) !== false) {
    if (
        preg_match("/.*[aeiou].*[aeiou].*[aeiou].*/", $line) === 1 &&
        hasLetterTwiceInARow($line) &&
        strpos($line, "ab") === false &&
        strpos($line, "cd") === false &&
        strpos($line, "pq") === false &&
        strpos($line, "xy") === false
    ) {
        $niceCount++;
    }

    if (
        hasPairs($line) &&
        hasRepeatingLetterWithAnotherInBetween($line)
    ) {
        $niceCount2++;
    }
}

echo "day 5.1: $niceCount <br>";
echo "day 5.2: $niceCount2 <br>";
