<?php

declare(strict_types=1);

$timerStart = 0.0;

function startTimer(): void
{
    global $timerStart;

    $timerStart = microtime(true);
}

function endTimer(): float
{
    global $timerStart;

    return microtime(true) - $timerStart;
}

function printDay(string $text): void
{
    $time = endTimer();
    $time = round($time * 1000, 3); // in ms

    echo $text . PHP_EOL;
    echo "Done in $time ms" . PHP_EOL . PHP_EOL;
}

function dd(mixed ...$stuffs): never
{
    var_dump(...$stuffs);

    exit;
}
