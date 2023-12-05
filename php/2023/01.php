<?php

declare(strict_types=1);

namespace FlorentPoujol\Adv2022\_01;

require_once './tools.php';

$handle = fopen('input/01.txt', 'r');

startTimer();
$sum = 0;

while (($line = trim((string) fgets($handle))) !== '') {
    $firstDigit = null;
    $lastDigit = null;

    foreach (str_split($line) as $char) {
        if (! is_numeric($char)) {
            continue;
        }

        $lastDigit = $char;

        if ($firstDigit === null) {
            $firstDigit = $lastDigit;
        }
    }

    if ($firstDigit === null || $lastDigit === null) {
        dd('error: first or last digit is null', $firstDigit, $lastDigit, $line);
    }

    $matches = [];
    preg_match('/^[a-z]*(\d).*(\d)[a-z]*$/i', $line, $matches);


    $sum += (int) ($firstDigit . $lastDigit);
}

printDay("01.1: $sum"); // 4.4ms

// --------------------------------------------------

rewind($handle);
startTimer();
$sum = 0;

function textDigitToIntDigit(string $string): string
{
    if (is_numeric($string)) {
        return $string;
    }

    return match ($string) {
        'one' => '1',
        'two' => '2',
        'three' => '3',
        'four' => '4',
        'five' => '5',
        'six' => '6',
        'seven' => '7',
        'eight' => '8',
        'nine' => '9',
        default => dd('error unmatched string : ', $string),
    };
}

while (($line = trim((string) fgets($handle))) !== '') {
    $matches = [];
    $capturingGroup = '(one|two|three|four|five|six|seven|eight|nine|\d)';
    preg_match("/{$capturingGroup}.*$/", $line, $matches);
    if (! isset($matches[1])) {
        dd('error: first match not found', $matches, $line);
    }
    $digitOne = textDigitToIntDigit($matches[1]);

    $matches = [];
    // note Florent : this works because by default, regexes are greedy and the .* will try to match as much as possible
    // leaving the capturing group the least possible things, which are then at the end of the string
    // The U option inverse this.
    preg_match("/^.*{$capturingGroup}/", $line, $matches);
    if (! isset($matches[1])) {
        dd('error: second match not found', $matches, $line);
    }
    $digitTwo = textDigitToIntDigit($matches[1]);

    // echo $digitOne . $digitTwo . ' ' . $line . PHP_EOL;

    $sum += (int) ($digitOne . $digitTwo);
}

printDay("01.2 regex: $sum"); // 2.1ms

