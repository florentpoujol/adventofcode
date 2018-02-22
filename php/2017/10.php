<?php
// http://adventofcode.com/2017/day/10

$lengths = [70, 66, 255, 2, 48, 0, 54, 48, 80, 141, 244, 254, 160, 108, 1, 41];
// $lengths = [3, 4, 1, 5]; // test

$list = range(0, 255);
// $list = [0, 1, 2, 3, 4]; // test
// $listSize = count($list);
$currentPosition = 0;
$skipSize = 0;

function knot($list, $lengths)
{
    global $currentPosition, $skipSize;
    $listSize = count($list);

    foreach ($lengths as $length) {
        if ($length > $listSize) {
            continue;
        }

        // extract portion to reverse
        $chunkToReverse = array_slice($list, $currentPosition, $length);
        $remainingElems = $length - count($chunkToReverse);
        if ($remainingElems > 0) { // pick remaining positions from the start of list
            $chunkToReverse = array_merge($chunkToReverse, array_slice($list, 0, $remainingElems));
        }
        $reversed = array_reverse($chunkToReverse);

        // put the reversed portions back
        $distanceToEnd = $listSize - $currentPosition;
        // size of the portion that will be replaced from currentPosition
        // up to the end of the list, or the end of the reversed array, whichever comes first
        $replacementLength = min($length, $distanceToEnd);
        array_splice($list, $currentPosition, $replacementLength, array_splice($reversed, 0, $replacementLength));

        // remove replaced element from the reversed array
        // and replace the rest (if any) from the start of list
        while (!empty($reversed)) {
            $replacementLength = min(count($reversed), $listSize);
            array_splice($list, 0, $replacementLength, array_splice($reversed, 0, $replacementLength));
        }

        $currentPosition += ($length + $skipSize);
        while ($currentPosition >= $listSize) {
            $currentPosition -= $listSize;
        }
        $skipSize++;
    }

    return $list;
}

$list = knot($list, $lengths);

$value = $list[0] * $list[1];

echo "Day 10.1: $value\n"; // 37442 too high

// day 2

function knotHash(string $input): string
{
    $input = str_split($input);
    $lengths = [];
    foreach ($input as $char) {
        $lengths[] = ord($char);
    }
    $lengths = array_merge($lengths, [17,31,73,47,23]);

    global $currentPosition, $skipSize;
    $currentPosition = 0;
    $skipSize = 0;
    $spareHash = range(0, 255);
    for ($i = 1; $i <= 64; $i++) {
        $spareHash = knot($spareHash, $lengths);
    }

    $temp = 0;
    $denseHash = [];
    foreach ($spareHash as $id => $value) {
        if ($id === 0) {
            $temp = $value;
        } elseif ($id % 16 === 0) {
            $denseHash[] = $temp;
            $temp = $value;
        } else {
            $temp ^= $value;
        }
    }
    $denseHash[] = $temp;

    $hash = "";
    foreach ($denseHash as $num) {
        $hex = dechex($num);
        if (strlen($hex) === 1) {
            $hex = "0$hex";
        }
        $hash .= $hex;
    }

    return $hash;
}

$input = "70,66,255,2,48,0,54,48,80,141,244,254,160,108,1,41";
// tests
//$input = "";
//$input = "AoC 2017";
//$input = "1,2,3";
//$input = "1,2,4";
$hash = knotHash($input);

echo "Day 10.2: $hash\n";
