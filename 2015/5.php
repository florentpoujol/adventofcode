<?php

$resource = fopen("5_input.txt", "r");

$niceCount = 0;
$niceCount2 = 0;

function hasLetterTwiceInARow($str) 
{
    for ($i=0; $i < strlen($str)-1; $i++) { 
        if ($str[$i] === $str[$i+1]) {
            return true;
        }
    }
    return false;
}

function hasPairs($str, $v = false)
{
    for ($i=0; $i < strlen($str)-1; $i++) {
        $chunk = $str[$i] . $str[$i+1];
        
        $pos = strpos( substr($str, $i+2), $chunk);
        
        if ($pos !== false) {
            if ($v)
                return $pos;
            else return true;
        }
    }
    return false;
}

function hasRepeatingLetterWithAnotherInBetween($str) 
{
    for ($i=0; $i < strlen($str)-2; $i++) { 
        if ($str[$i] === $str[$i+2] && $str[$i] !== $str[$i+1]) {
            return true;
        }
    }
    return false;
}

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    if (
        preg_match("/.*[aeiou].*[aeiou].*[aeiou].*/", $line) === 1 &&
        hasLetterTwiceInARow($line) &&
        (
            strpos($line, "ab") === false &&
            strpos($line, "cd") === false &&
            strpos($line, "pq") === false &&
            strpos($line, "xy") === false
        )
    ) {
        $niceCount++;
    }

    if (
        hasPairs($line) &&
        hasRepeatingLetterWithAnotherInBetween($line)
    ) {
        $niceCount2++;
    }

    if (hasPairs($line) !== hasPairs2($line)) {
        var_dump("wrong value", hasPairs($line, true), hasPairs2($line, true), $line);
    }
}

echo "day 5.1: $niceCount <br>";
echo "day 5.2: $niceCount2 <br>"; // the aswer is 55 but my code gives me 52...
