<?php

$res = fopen("70_input.txt", "r");

function hasAbba($str)
{
    $str = str_replace(["[", "]"], "", $str);
    $len = strlen($str);
    // var_dump($str, $len);
    if ($len >= 4) {
        for ($i=0; $i < $len-3; $i++) { 
            $chunk1 = $str[$i].$str[$i+1];
            $chunk2 = $str[$i+2].$str[$i+3];
            $chunk2r = $str[$i+3].$str[$i+2]; 
            
            if ($chunk1 !== $chunk2 && $chunk1 === $chunk2r) {
                return true;
            }
        }
    }

    return false;
}

$abas = [];

function getABA($str)
{
    global $abas;

    $str = str_replace(["[", "]"], "", $str);
    $len = strlen($str);
    // var_dump($str, $len);
    if ($len >= 3) {
        for ($i=0; $i < $len-2; $i++) { 
            $chunk = $str[$i].$str[$i+1].$str[$i+2];
            $compl = $str[$i+1].$str[$i].$str[$i+1];
            
            if ($str[$i] === $str[$i+2] && $str[$i] !== $str[$i+1]) {
                $abas[$chunk] = $compl;
            }
        }
    }

    return false;
}

function hasBab($str, $abas) 
{
    foreach ($abas as $aba) {
        if (strpos($str, $aba) !== false) {
            return true;
        }
    }
    return false;
}



$tls = 0;
$ssl = 0;

while (($line = fgets($res)) !== false) {
    
    $matches = [];
    preg_match_all("/\[?[a-z]+\]?/", $line, $matches);

    $hasTLS = false;
    foreach ($matches[0] as $str) {
        $isHypernet = (strpos($str, "[") === 0);
        if ($isHypernet && hasAbba($str)) {
            $hasTLS = false;
            break;
        }

        if (hasAbba($str)) {
            $hasTLS = true;
        }
    }

    if ($hasTLS) {
        $tls++;
    }


    $abas = [];
    foreach ($matches[0] as $str) {
        $isHypernet = (strpos($str, "[") === 0);
        if (! $isHypernet) {
            getABA($str);
        }
    }
    if (count(array_keys($abas)) === 0) {
        continue;
    }

    foreach ($matches[0] as $str) {
        $isHypernet = (strpos($str, "[") === 0);
        if ($isHypernet && hasBab($str, $abas)) {
            $ssl++;
            break;
        }
    }
}

echo "day 7.1: $tls <br>";
echo "day 7.1: $ssl <br>";
