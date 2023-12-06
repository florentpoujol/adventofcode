<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/04.txt', 'r');

startTimer();
$sum = 0;

/**
 * @return array<int>
 */
function cleanNumbers(string $numbers): array
{
    $aNumbers = explode(' ', $numbers);
    $aNumbers = array_map(fn (string $value) => (int) $value, $aNumbers);
    $aNumbers = array_filter($aNumbers, fn (int $value) => $value !== 0); // a single digit number will be read as a 0 and the other digit

    return $aNumbers;
}

while (($line = trim((string) fgets($handle))) !== '') {
    [$card, $sets] = explode(': ', $line, 2);
    $card = (int) str_replace('Card ', '', $card);

    [$winningNumbers, $cardNumbers] = explode(' | ', $sets, 2);

    $winningNumbers = cleanNumbers($winningNumbers);
    $cardNumbers = cleanNumbers($cardNumbers);

    /** @var array $commonNumbers */
    $commonNumbers = array_intersect($winningNumbers, $cardNumbers);

    $count = count($commonNumbers);
    $points = $count === 0 ? 0 : 1;
    for (--$count; $count > 0; --$count) {
        $points *= 2;
    }

    // display($card, $points);
    // var_dump($commonNumbers);

    $sum += $points;
}

printDay("04.1: $sum"); // 6.6 ms

// --------------------------------------------------

rewind($handle);
startTimer();
$sum = 0;

$cards = [];

while (($line = trim((string) fgets($handle))) !== '') {
    [$card, $sets] = explode(': ', $line, 2);
    $card = (int) str_replace('Card ', '', $card);

    [$winningNumbers, $cardNumbers] = explode(' | ', $sets, 2);

    $winningNumbers = cleanNumbers($winningNumbers);
    $cardNumbers = cleanNumbers($cardNumbers);

    /** @var array $commonNumbers */
    $commonNumbers = array_intersect($winningNumbers, $cardNumbers);

    $wonCardIndexes = [];
    $commonCount = count($commonNumbers);
    if ($commonCount > 0) {
        $count = $commonCount;
        for ($i = $card + 1; $count > 0; $count--) {
            $wonCardIndexes[] = $i;
            $i++;
        }
    }

    // keys starts at 1 !
    $cards[$card] = [ // this array is the original
        'won_card_indexes' => $wonCardIndexes,
    ];
}

$remainingCardsIndex = array_keys($cards);

$sum = 0;
while ($remainingCardsIndex !== []) {
    $sum++;

    $cardIndex = array_pop($remainingCardsIndex);

    // note Florent :
    // using array_shift() instead of array_pop() makes the $remainingCardsIndex
    // always fill up (up to several millions) and the script doesn't terminate after many minutes.

    // Yet with array_pop, the array nerver fills up with more than 250 values.

    // I can understand array_pop() is faster because the keys do not have to be changed every time,
    // but I do not understand the behavior difference...

    $card = $cards[$cardIndex];

    foreach ($card['won_card_indexes'] as $index) {
        $remainingCardsIndex[] = $index; // this is MUCH faster than using array_merge() or even the newer [...$a1, ...$a2] syntax
    }

    // display($sum, $cardIndex, implode(' ', $remainingCardsIndex), $sum);
    if ($sum % 100 === 0) {
        echo '.';
    }
    if ($sum % 10_000 === 0) {
        echo ' ' . count($remainingCardsIndex) . PHP_EOL;
    }
};

printDay("04.2: $sum"); // 5.5 s
