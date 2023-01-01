<?php

/*
 * this is really the same as part 1 except that all numbers are strings,
 * and handled by the bcmath extension
 *
 * This may be an actual solution but I don't know, some numbers get impossibly big
 * so the script, while fast for the few first loops, gets also impossibly slow
 * and didn't complete even after running for several hours (on a single 3.5GHz CPU).
 */

declare(strict_types=1);

namespace FlorentPoujol\Adv2022\_112;

use Closure;

require_once 'tools.php';

$handle = fopen('11_input.txt', 'r');

startTimer();

final class Monkey {
    /** @var array<string> */
    public array $items;

    /** @var Closure(string $old): string */
    public Closure $operation;

    /** @var Closure(string $worryLevel): bool */
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
    public function test(string $worryLevel): int
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
        $currentMonkey->items = explode(', ', $matches[1]);

        continue;
    }

    if (preg_match('/^Operation: new = old ([+*]) (\d+|old)$/', $line, $matches)) {
        if ($matches[1] === '+') {
            $currentMonkey->operation = static function (string $old) use ($matches): string {
                return bcadd($old, $matches[2]);
            };
        } else {
            $currentMonkey->operation = static function (string $old) use ($matches): string {
                if ($matches[2] === 'old') {
                    return bcmul($old, $old);
                }

                return bcmul($old, $matches[2]);
            };
        }

        continue;
    }

    if (preg_match('/^Test: divisible by (\d+)$/', $line, $matches)) {
        $currentMonkey->testCondition = static fn (string $worryLevel): bool => bcmod($worryLevel, $matches[1]) === '0';

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

ini_set('memory_limit', '-1');

echo 'start ' . date('H:i:s') . PHP_EOL;
$roundCount = 0;
while(++$roundCount <= 200) {
    foreach ($monkeys as $monkey) {
        $items = $monkey->items;
        foreach ($items as $item) {
            array_shift($monkey->items);
            $monkey->totalInspectedItemCount++;
            $item = ($monkey->operation)($item);

            $newMonkeyId = $monkey->test($item);
            $monkeys[$newMonkeyId]->items[] = $item;
        }
    }

    // if ($roundCount % 10 === 0) {
    //     echo "roundcount $roundCount | " . date('H:i:s') . PHP_EOL;
    // }
}
echo 'done ' . date('H:i:s') . PHP_EOL;
unset($monkey);

// dumpMonkeys();
// exit;

$inspectedItems = array_map(fn (MOnkey $monkey): int => $monkey->totalInspectedItemCount, $monkeys);
rsort($inspectedItems);
$monkeyBusiness = $inspectedItems[0] * $inspectedItems[1];

printDay("11.2 : $monkeyBusiness");
