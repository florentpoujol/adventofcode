<?php

declare(strict_types=1);

require_once 'tools.php';

$handle = fopen('06_input.txt', 'r');

startTimer();

function isStartfPacketMarker(string $string): bool
{
    if (strlen($string) !== 4) {
        return false;
    }

    // return count(array_unique(str_split($string))) === 4; // much slower !
    return
        $string[0] !== $string[1]
        && $string[0] !== $string[2]
        && $string[0] !== $string[3]
        && $string[1] !== $string[2]
        && $string[1] !== $string[3]
        && $string[2] !== $string[3];
}

$buffer = '';
while (($char = fgetc($handle)) !== false) {
    if ($char === PHP_EOL) {
        break;
    }

    $buffer .= $char;
    if (isStartfPacketMarker(substr($buffer, -4))) {
        break;
    }
}

printDay('06.1 : ' . strlen($buffer));

// --------------------------------------------------


startTimer();

function isStartOfMessageMarker(string $string): bool
{
    // version 1 (takes 7.3 ms)
    // return count(array_unique(str_split($string))) === 14; // about 7.3 ms

    // version 2 (takes 5.8 ms)
    // this expects the string to be exactly 14 char long
    for ($i = 0; $i <= 12; $i++) {
        $letter = $string[$i];
        for ($j = $i + 1; $j <= 13; $j++) {
            if ($letter === $string[$j]) {
                return false;
            }
        }
    }

    return true;
}

$buffer = '';
rewind($handle);
$bufferLength = 0;
while (($char = fgetc($handle)) !== false) {
    if ($char === PHP_EOL) {
        break;
    }

    $buffer .= $char;

    // version 1
    // if (isStartOfMessageMarker(substr($buffer, -14))) {
    //     break;
    // }

    // version 2
    $bufferLength++;
    if ($bufferLength >= 14 && isStartOfMessageMarker(substr($buffer, -14))) {
        break;
    }
}

printDay('06.2 : ' . strlen($buffer)); // 3613
