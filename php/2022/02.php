<?php

declare(strict_types=1);

require_once 'tools.php';

$handle = fopen('02_input.txt', 'r');

// A = X = Rock = 1 point
// B = Y = Paper = 2 points
// C = Z = Scissor = 3 points

// loose = 0 point
// draw = 3 points
// win = 6 points

$scores = [
    'A' => 1,
    'B' => 2,
    'C' => 3,

    'X' => 1,
    'Y' => 2,
    'Z' => 3,

    'loose' => 0,
    'draw' => 3,
    'win' => 6,
];

$outcomes = [
    'A X' => 'draw',
    'A Y' => 'win',
    'A Z' => 'loose',

    'B X' => 'loose',
    'B Y' => 'draw',
    'B Z' => 'win',

    'C X' => 'win',
    'C Y' => 'loose',
    'C Z' => 'draw',
];

startTimer();

$totalScore = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    [, $own] = explode(' ', $line, 2);

    $totalScore += $scores[$own] + $scores[$outcomes[$line]];
}

printDay("02.1 : $totalScore"); // 12156

// --------------------------------------------------

// X = needs to loose
// Y = needs to draw
// Z = needs to win

// A = X = Rock = 1 point
// B = Y = Paper = 2 points
// C = Z = Scissor = 3 points

$shapesToPlay = [
    'A X' => 'C',
    'A Y' => 'A',
    'A Z' => 'B',

    'B X' => 'A',
    'B Y' => 'B',
    'B Z' => 'C',

    'C X' => 'B',
    'C Y' => 'C',
    'C Z' => 'A',
];

$scores = [
    'A' => 1,
    'B' => 2,
    'C' => 3,

    'X' => 0, // win
    'Y' => 3, // draw
    'Z' => 6, // win
];

startTimer();
rewind($handle);

$totalScore = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    [, $outcome] = explode(' ', $line, 2);

    $own = $shapesToPlay[$line];

    $totalScore += $scores[$own] + $scores[$outcome];
}

printDay("02.2 : $totalScore"); // 10910835
