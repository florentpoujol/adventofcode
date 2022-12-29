<?php

declare(strict_types=1);

namespace FlorentPoujoul\Adv2022\_11;

use Closure;

require_once 'tools.php';

$handle = fopen('11_input.txt', 'r');

startTimer();

final class Monkey {
    /** @var array<int> */
    public array $items;

    /** @var Closure(int $old): int */
    public Closure $operation;

    /** @var Closure(int $worryLevel): bool */
    public Closure $testCondition;

    /** @var array<int, int> */
    public array $destinationMonkeyIds = [
        0 => 0, // if test is false
        1 => 0, // if test is true
    ];

    public int $totalInspectedItemCount = 0;

    /**
     * @return int The id of the monkey that receive the item
     */
    public function test(int $worryLevel): int
    {
        return $this->destinationMonkeyIds[(int) ($this->testCondition)($worryLevel)];
    }
}

function dumpMonkeys(): void
{
    global $monkeys;

    foreach ($monkeys as $i => $monkey) {
        echo "Monkey $i: " . implode(', ', $monkey->items) . ' | Inspected items: ' . $monkey->totalInspectedItemCount . PHP_EOL;
    }
}

/** @var array<Monkey> $monkeys */
$monkeys = [];

/** @var null|Monkey $currentMonkey */
$currentMonkey = null;
$matches = [];

while (($line = fgets($handle)) !== false) {
    $line = trim($line);
    if ($line === '') {
        continue;
    }

    if (preg_match('/^Monkey (\d):$/', $line, $matches)) {
        $currentMonkey = new Monkey();
        $monkeys[(int) $matches[1]] = $currentMonkey;

        continue;
    }

    if (preg_match('/^Starting items: ([0-9, ]+)$/', $line, $matches)) {
        $currentMonkey->items = array_map('intval', explode(', ', $matches[1]));

        continue;
    }

    if (preg_match('/^Operation: new = old ([+*]) (\d+|old)$/', $line, $matches)) {
        if ($matches[1] === '+') {
            $currentMonkey->operation = static function (int $old) use ($matches): int {
                return $old + (int) $matches[2];
            };
        } else {
            $currentMonkey->operation = static function (int $old) use ($matches): int {
                if ($matches[2] === 'old') {
                    return $old * $old;
                }

                return $old * (int) $matches[2];
            };
        }

        continue;
    }

    if (preg_match('/^Test: divisible by (\d+)$/', $line, $matches)) {
        $currentMonkey->testCondition = static fn (int $worryLevel) => ($worryLevel % (int) $matches[1]) === 0;

        continue;
    }

    if (preg_match('/^If true: throw to monkey (\d+)$/', $line, $matches)) {
        $currentMonkey->destinationMonkeyIds[1] = (int) $matches[1];

        continue;
    }

    if (preg_match('/^If false: throw to monkey (\d+)$/', $line, $matches)) {
        $currentMonkey->destinationMonkeyIds[0] = (int) $matches[1];
    }
}

$part2Monkeys = [];
foreach ($monkeys as $monkey) {
    $part2Monkeys[] = clone $monkey;
}

$roundCount = 0;
while(++$roundCount <= 20) {
    foreach ($monkeys as $monkey) {
        $items = $monkey->items;
        foreach ($items as $item) {
            array_shift($monkey->items);
            $monkey->totalInspectedItemCount++;
            $item = ($monkey->operation)($item);

            $item = (int) ($item / 3);

            $newMonkeyId = $monkey->test($item);
            $monkeys[$newMonkeyId]->items[] = $item;
        }

    }
}
unset($monkey);

// dumpMonkeys();

$inspectedItems = array_map(fn (MOnkey $monkey): int => $monkey->totalInspectedItemCount, $monkeys);
rsort($inspectedItems);
$monkeyBusiness = $inspectedItems[0] * $inspectedItems[1];

printDay("11.1 : $monkeyBusiness");
