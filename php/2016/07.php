<?php
// http://adventofcode.com/2016/day/7

function hasAbba(string $str): bool
{
    // this does check if there is no abba in the square bracket part...
    $str = str_replace(["[", "]"], "", $str);
    $len = strlen($str);
    // var_dump($str, $len);
    if ($len >= 4) {
        for ($i = 0; $i < $len - 3; $i++) {
            $chunk1 = $str[$i] . $str[$i+1];
            $chunk2 = $str[$i+2] . $str[$i+3];
            $chunk2r = $str[$i+3] . $str[$i+2];
            
            if ($chunk1 !== $chunk2 && $chunk1 === $chunk2r) {
                return true;
            }
        }
    }

    return false;
}

function getABA($str)
{
    global $ABAs;

    $str = str_replace(["[", "]"], "", $str);
    $len = strlen($str);
    // var_dump($str, $len);
    if ($len >= 3) {
        for ($i=0; $i < $len-2; $i++) { 
            $chunk = $str[$i].$str[$i+1].$str[$i+2];
            $compl = $str[$i+1].$str[$i].$str[$i+1];
            
            if ($str[$i] === $str[$i+2] && $str[$i] !== $str[$i+1]) {
                $ABAs[$chunk] = $compl;
            }
        }
    }

    return false;
}

function hasBab($str, $ABAs)
{
    foreach ($ABAs as $aba) {
        if (strpos($str, $aba) !== false) {
            return true;
        }
    }
    return false;
}


$ABAs = [];
$tls = 0;
$ssl = 0;
$res = fopen("07_input.txt", "r");
$matches = [];
while (($line = fgets($res)) !== false) {
    preg_match_all("/\[?[a-z]+\]?/", trim($line), $matches);
    // the regex split the line into the non-hypernet / hypernet sections
    // matches[0] contains all the chunks of the line
    // the hypernet sections begins and end by squere brackets

    $hasTLS = false;
    foreach ($matches[0] as $chunk) {
        $isHypernet = strpos($chunk, "[") === 0;
        if ($isHypernet && hasAbba($chunk)) {
            $hasTLS = false;
            break;
        }

        if (hasAbba($chunk)) {
            $hasTLS = true;
        }
    }

    if ($hasTLS) {
        $tls++;
    }


    $ABAs = [];
    foreach ($matches[0] as $chunk) {
        $isHypernet = strpos($chunk, "[") === 0;
        if (! $isHypernet) {
            getABA($chunk);
        }
    }

    if (empty($ABAs)) {
        continue;
    }

    foreach ($matches[0] as $chunk) {
        $isHypernet = (strpos($chunk, "[") === 0);
        if ($isHypernet && hasBab($chunk, $ABAs)) {
            $ssl++;
            break;
        }
    }
}

echo "day 7.1: $tls <br>";
echo "day 7.2: $ssl <br>";
