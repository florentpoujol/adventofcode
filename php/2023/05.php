<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/05.txt', 'r');

final class Range {
    private readonly int $sourceEnd;

    public function __construct(
        private readonly int $destinationStart,
        public readonly int $sourceStart,
        public readonly int $length,
    ) {
        $this->sourceEnd = $this->sourceStart + $this->length;
    }

    public function isSourceInRange(int $source): bool
    {
        return $source >= $this->sourceStart && $source <= $this->sourceEnd;
    }

    public function getDestination(int $source): int
    {
        $offset = $source - $this->sourceStart;

        return $this->destinationStart + $offset;
    }
}

/**
 * @param array<Range> $map
 */
function findDestinationInRanges(array $map, int $source): int
{
    foreach ($map as $range) {
        if ($range->isSourceInRange($source)) {
            return $range->getDestination($source);
        }
    }

    return $source;
}

/** @var array<int> $seeds */
$seeds = [];

/** @var array<array{ranges: array<Range>, source_min: int, source_max: int}> $maps */
$maps = [
    'seed-to-soil' => [],
    'soil-to-fertilizer' => [],
    'fertilizer-to-water' => [],
    'water-to-light' => [],
    'light-to-temperature' => [],
    'temperature-to-humidity' => [],
    'humidity-to-location' => [],
];


while (($line = fgets($handle)) !== false) {
    if (str_starts_with($line, 'seeds:')) {
        $seeds = explode(' ', substr($line, 7));
        $seeds = array_map('intval', $seeds);

        continue;
    }

    if (trim($line) === '') {
        continue;
    }

    if (str_contains($line, ' map:')) {
        $mapName = trim(str_replace(' map:', '', $line));

        while (($_line = trim((string) fgets($handle))) !== '') {
            [$dest, $source, $length] = explode(' ', $_line);

            $maps[$mapName]['ranges'] ??= [];
            $maps[$mapName]['ranges'][] = new Range(
                (int) $dest,
                (int) $source,
                (int) $length,
            );
        }

        continue;
    }

    dd("error reading the file at line '$line'");
}

// now go over the maps to register the min and max source values
// so that we can first check these and not go through all ranges if it's not necessary
foreach ($maps as & $map) { // /!\ REFERENCE
    $map['source_min'] = PHP_INT_MAX;
    $map['source_max'] = -1;

    foreach ($map['ranges'] as $range) {
        $min = $range->sourceStart;
        $max = $range->sourceStart + $range->length;

        $map['source_min'] = min($map['source_min'], $min);
        $map['source_max'] = max($map['source_max'], $max);
    }
}
unset($map); // because of reference

startTimer();

$smallestLocation = -1;

foreach ($seeds as $seed) {
    $destination = $seed;
    foreach ($maps as $map) {
        $destination = findDestinationInRanges($map['ranges'], $destination);
    }

    if ($smallestLocation === -1 || $destination < $smallestLocation) {
        $smallestLocation = $destination;
    }
}

printDay("05.1: $smallestLocation"); // 0.55 ms

// --------------------------------------------------

rewind($handle);
startTimer();

$smallestLocation = PHP_INT_MAX;

$seedRanges = array_chunk($seeds, 2);

/** @var array<string, array<int, int>> $destinationCachePerMapName */
$destinationCachePerMapName = [
    // keys are map names
    // values are array<int, int>, key is the source, value is the destination
];

foreach ($seedRanges as $i => $seedRange) {
    $minSeed = $seedRange[0];
    $length = $seedRange[1];
    display("starting for seed range $i", $seedRange[0], $seedRange[1]);

    $j = 0;
    for (; $length >= 0; --$length) {
        $destination = $minSeed + $length;
        foreach ($maps as $mapName => $map) {
            if ($destination < $map['source_min'] || $destination > $map['source_max']) {
                continue;
                // this "optimisation" doesn't work at all because the function findDestinationInRanges() still gets called as many times as before...
            }

            $destination = findDestinationInRanges($map['ranges'], $destination);
        }

        if ($destination < $smallestLocation) {
            $smallestLocation = $destination;
        }

        $j++;
        if ($j % 10_000 === 0) {
            echo '.';
        }
        if ($j % 500_000 === 0) {
            echo "length remaining: $length" . PHP_EOL;
            exit;
        }
    }
}

// Note : /!\ this doesn't works ! /!\
// is infinitely too slow, like 10 seconds to check a million seeds, and yet there is a total of a few billions seeds...

printDay("05.1: $smallestLocation"); //
