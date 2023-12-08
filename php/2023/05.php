<?php

declare(strict_types=1);

require_once './tools.php';

$handle = fopen('input/05.txt', 'r');

final class Range {
    private readonly int $sourceEnd;
    private readonly int $destinationEnd;

    public function __construct(
        private readonly int $destinationStart,
        public readonly int $sourceStart,
        public readonly int $length,
    ) {
        $this->sourceEnd = $this->sourceStart + ($this->length - 1);
        $this->destinationEnd = $this->destinationStart + ($this->length - 1);
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

    public function isDestinationInRange(int $destination): bool
    {
        return $destination >= $this->destinationStart && $destination <= $this->destinationEnd;
    }

    public function getSource(int $destination): int
    {
        $offset = $destination - $this->destinationStart;

        return $this->sourceStart + $offset;
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

// $smallestLocation = PHP_INT_MAX;
// $seedRanges = array_chunk($seeds, 2);
//
// foreach ($seedRanges as $i => $seedRange) {
//     $minSeed = $seedRange[0];
//     $length = $seedRange[1];
//     display("starting for seed range $i", $seedRange[0], $seedRange[1]);
//
//     $j = 0;
//     for (; $length >= 0; --$length) {
//         $destination = $minSeed + $length;
//         foreach ($maps as $mapName => $map) {
//             if ($destination < $map['source_min'] || $destination > $map['source_max']) {
//                 continue;
//                 // this "optimisation" doesn't work at all because the function findDestinationInRanges() still gets called as many times as before...
//             }
//
//             $destination = findDestinationInRanges($map['ranges'], $destination);
//         }
//
//         if ($destination < $smallestLocation) {
//             $smallestLocation = $destination;
//         }
//
//         $j++;
//         if ($j % 10_000 === 0) {
//             echo '.';
//         }
//         if ($j % 500_000 === 0) {
//             echo "length remaining: $length" . PHP_EOL;
//             exit;
//         }
//     }
// }
// Note : /!\ this doesn't works ! /!\
// is infinitely too slow, like 10 seconds to check a million seeds, and yet there is a total of a few billions seeds...


// All of this commented code above may be right but is way too slow.
// Instead of finding the location based on the seeds, we will try every location, starting from 0, until we find a matching seed.
// (I got the idea from Reddit)

$location = 0;
$seed = 0;

$reversedMaps = array_reverse($maps, true);

/**
 * @param array<Range> $map
 */
function findSourceInRanges(array $map, int $destination): int
{
    foreach ($map as $range) {
        if ($range->isDestinationInRange($destination)) {
            return $range->getSource($destination);
        }
    }

    return $destination;
}

$seedRanges = array_chunk($seeds, 2);

foreach ($seedRanges as & $seedRange) { // /!\ REFERENCE
    $seedRange[2] = $seedRange[0] + ($seedRange[1] - 1);
}
unset($seedRange);

do {
    $location++;

    $destination = $location;
    foreach ($reversedMaps as $mapName => $reversedMap) {
        $source = $destination;
        $destination = findSourceInRanges($reversedMap['ranges'], $destination);
        // display($mapName, $destination, $source);
    }
    // $destination is now a seed

    foreach ($seedRanges as $seedRange) {
        if ($destination >= $seedRange[0] && $destination <= $seedRange[2]) { // seed is within range
            $seed = $destination;
            // display($location, $seed);
            break(2);
        }
    }

    if ($location % 10_000 === 0) {
        echo '.';
    }
    if ($location % 1_000_000 === 0) {
        echo PHP_EOL;
    }
} while (true);

printDay("05.1: $location | seed ($seed)"); // 669 s / 11 m (location 37806486)
