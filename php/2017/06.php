<?php
$blocksPerBankId = [2, 8, 8, 5, 4, 2, 3, 1, 5, 5, 1, 2, 15, 13, 5, 14];
//$blocksPerBankId = [0, 2, 7, 0]; // test

$states = [$blocksPerBankId];
$reallocationCount = 0;

$infLoopState = null;
$infLoopSteps = 0;

while (true) {
    // find biggest bank
    $biggestBankId = -1;
    $maxBlockCount = -1;
    foreach ($blocksPerBankId as $bankId => $blockCount) {
        if ($blockCount > $maxBlockCount) {
            $maxBlockCount = $blockCount;
            $biggestBankId = $bankId;
        }
    }

    // reallocate
    $banksCount = count($blocksPerBankId);
    $blocksPerBankId[$biggestBankId] = 0;

    if ($biggestBankId === $banksCount - 1) {
        // the biggest bank is the last
        $biggestBankId = -1; // make the loop starts at the first bank
    }

    for ($bankId = $biggestBankId + 1; $bankId < $banksCount && $maxBlockCount > 0; $bankId++) {
        $blocksPerBankId[$bankId]++;
        $maxBlockCount--;

        if ($bankId === $banksCount - 1) {
            // this si the last bank, wrap back to the first
            $bankId = -1;
        }
    }

    if ($infLoopState !== null) {
        $infLoopSteps++;
    } else {
        $reallocationCount++;
    }

    if ($infLoopState === $blocksPerBankId) {
        break;
    }

    // check if the current state (blocksPerBankId) has already be seen
    if ($infLoopState === null && in_array($blocksPerBankId, $states)) {
        $infLoopState = $blocksPerBankId;
    }

    $states[] = $blocksPerBankId;
}

//var_dump($states);

echo "Day 6.1: $reallocationCount \n";
echo "Day 6.2: $infLoopSteps \n";