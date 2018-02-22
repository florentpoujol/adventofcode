<?php
// http://adventofcode.com/2016/day/14

function run(bool $isPart2)
{
    $input = "ahsbgdzn";
    // $input = "abc"; // test

    $chunksToLookFor = []; // array of ["originalHash" => "", "chunk" => "", remainingtests = 1000]
    $keys = [];
    $keyCount = 0;

    $i = 0;
    while ($keyCount < 64) {
        $hash = md5( $input . $i );
        if ($isPart2) {
            $j = 0;
            while (++$j <= 2016 && ($hash = md5($hash)));
        }

        foreach ($chunksToLookFor as $chunk => &$allDataForThisChunk) {
            $dataCount = count($allDataForThisChunk);
            for ($datumId = 0; $datumId < $dataCount; $datumId++) {
                $datum = &$allDataForThisChunk[$datumId];

                if (strpos($hash, (string)$chunk) !== false) { // $chunk is a number
                    $keys[$datum["originalId"] . "_$i"] = $datum["originalHash"];
                    $keyCount++;
                    $datum["remainingTests"] = -1; // mark it as to be removed, just below
                    // var_dump($datum);
                    // echo "5: $datum[originalHash] ($i) $chunk | $hash | $datum[remainingTests]\n";
                }

                $datum["remainingTests"]--;
                if ($datum["remainingTests"] <= 0) {
                    array_splice($allDataForThisChunk, $datumId, 1);
                    $datumId--;
                    $dataCount--;
                }
            }
        }

        // 29 = 31 (last id in an md5 strings - 3
        for ($charId = 0; $charId <= 29; $charId++) {
            if (
                $hash[$charId] === $hash[$charId + 1] &&
                $hash[$charId] === $hash[$charId + 2]
            ) {
                $chunk = str_repeat($hash[$charId], 5);
                if (!isset($chunksToLookFor[$chunk])) {
                    $chunksToLookFor[$chunk] = [];
                }
                $chunksToLookFor[$chunk][] = [
                    "originalId" => $i,
                    "originalHash" => $hash,
                    "remainingTests" => 1000,
                ];
                // echo "tripple: $hash ($i) $chunk\n";
                break;
            }
        }

        $i++;
    }

    return $keys;
}

$keys = run(false);
// var_dump($keys);
ksort($keys, SORT_NATURAL);
$ks = array_keys($keys);
// var_dump($ks);

$num = explode("_", $ks[63])[0];
echo "Day 14.1: $num\n";

// part 2
$keys = run(true);
ksort($keys, SORT_NATURAL);
$ks = array_keys($keys);
$num = explode("_", $ks[63])[0];
echo "Day 14.2: $num\n";
