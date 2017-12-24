<?php
// http://adventofcode.com/2015/day/11

$input = "hxbxwxba"; // ghjaabcc

// test
//$input = "abcdefgh"; // next = abcdffaa
//$input = "ghijklmn"; // next =  ghjaabcc

$alphabet = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
$indexPerLetter = array_flip($alphabet);

function isValid($str)
{
    $a = str_split($str);

    if (in_array("i", $a) || in_array("o", $a) || in_array("l", $a)) {
        return false;
    }

    global $indexPerLetter;

    // check for increasing straight of at least three letters
    $isValid = false;
    for ($i = 0; $i < 6; $i++) {
        $index = $indexPerLetter[$a[$i]];

        $index2 = $indexPerLetter[$a[$i+1]];
        if ($index2 !== $index + 1) {
            continue;
        }

        $index2 = $indexPerLetter[$a[$i+2]];
        if ($index2 !== $index + 2) {
            continue;
        }

        $isValid = true;
        break;
    }

    if (! $isValid) {
        return false;
    }

    // check for at least two different, non-overlapping pairs of letters
    $firstPair = "";
    for ($i = 0; $i < 7; $i++) {
        if ($a[$i + 1] === $a[$i]) {
            $pair = $a[$i] . $a[$i + 1];
            if ($firstPair === "") {
                $firstPair = $pair;
                $i++;
            } elseif ($pair !== $firstPair) {
                return true;
            }
        }
    }

    return false;
}

function increment($str, $pos = 7)
{
    global $alphabet, $indexPerLetter;

    $letter = $str[$pos];
    if ($letter === "z") {
        if ($pos === 0) {
            var_dump("ERROR first letter is now Z");
            return "abcdffaa"; // arbitrary string that is valid and thus will end the while loop;
        }
        $str = increment($str, $pos-1);
        $str[$pos] = "a";
        return $str;
    }

    $index = $indexPerLetter[$letter];
    $letter = $alphabet[$index+1];
    if ($letter === "i" || $letter === "l" || $letter === "o") {
        // not necessary but probably speedup slightly the algorithm
        $letter = $alphabet[$index+2];
    }
    $str[$pos] = $letter;
    return $str;
}

$str = $input;

while (! isValid($str)) {
    $str = increment($str);
}

echo "day 11.1: $str <br>";

do {
    $str = increment($str);
} while (! isValid($str));

echo "day 11.2: $str  <br>";
