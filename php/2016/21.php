<?php
// https://adventofcode.com/2016/day/21

$test = ""; $password = "abcdefgh";
// $test = "_test"; $password = "abcde";
$password = str_split($password);

$instructions = explode("\n", file_get_contents("21_input$test.txt"));
$matches = [];
$states = [];
foreach ($instructions as $instruction) {
    if (preg_match("/swap position ([0-9]+) with position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $tmp = $password[$pos1];
        $password[$pos1] = $password[$pos2];
        $password[$pos2] = $tmp;
    }
    elseif (preg_match("/swap letter ([a-z]) with letter ([a-z])/", $instruction, $matches) === 1) {
        $keys1 = array_fill_keys(array_keys($password, $matches[1]), $matches[2]);
        $keys2 = array_fill_keys(array_keys($password, $matches[2]), $matches[1]);
        $password = array_replace($password, $keys1, $keys2);
    }
    elseif (preg_match("/rotate (left|right) ([0-9]+) steps?/", $instruction, $matches) === 1) {
        $stepsCount = (int)$matches[2];
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            if ($matches[1] === "right") {
                $newId = ($id + $stepsCount) % $passwordLength;
            } else {
                $newId = $id - $stepsCount;
                if ($newId < 0) {
                    $newId += $passwordLength; // lets's hope steps count isn't > passwordLength
                }
            }
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    }
    elseif (preg_match("/rotate based on position of letter ([a-z])/", $instruction, $matches) === 1) {
        $index = array_search($matches[1], $password);
        $stepsCount = $index + 1;
        if ($index >= 4) {
            $stepsCount++;
        }
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            $newId = ($id + $stepsCount) % $passwordLength;
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    }
    elseif (preg_match("/reverse positions ([0-9]+) through ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $reversed = array_splice($password, $pos1, ($pos2 - $pos1) + 1);
        $reversed = array_reverse($reversed);
        array_splice($password, $pos1, 0, $reversed);
    }
    elseif (preg_match("/move position ([0-9]+) to position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $letter = array_splice($password, $pos1, 1); // actually an array with 1 letter
        array_splice($password, $pos2, 0, $letter);
    }
    $states[] = implode("", $password);
}

$password = implode("", $password);
echo "Day 21.1: $password\n"; // agcebfdh

// part 2

$password = "fbgdceah";
// $password = "agcebfdh"; // test, but with the input instructions   should give abcdefgh
// $password = "decab"; // test (also uncomment the test above to get the correct instructions)
$password = str_split($password);

// $states = array_reverse($states); // for debug
$instructions = array_reverse($instructions);
foreach ($instructions as $stateId => $instruction) {
    if (preg_match("/swap position ([0-9]+) with position ([0-9]+)/", $instruction, $matches) === 1) {
        // nothing to change here
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $tmp = $password[$pos1];
        $password[$pos1] = $password[$pos2];
        $password[$pos2] = $tmp;
    }
    elseif (preg_match("/swap letter ([a-z]) with letter ([a-z])/", $instruction, $matches) === 1) {
        // nothing to change here
        $keys1 = array_fill_keys(array_keys($password, $matches[1]), $matches[2]);
        $keys2 = array_fill_keys(array_keys($password, $matches[2]), $matches[1]);
        $password = array_replace($password, $keys1, $keys2);
    }
    elseif (preg_match("/rotate (left|right) ([0-9]+) steps?/", $instruction, $matches) === 1) {
        $stepsCount = (int)$matches[2];
        $passwordLength = count($password);
        $newPassword = [];
        foreach ($password as $id => $letter) {
            if ($matches[1] === "left") { // left become right and vice-versa
                $newId = ($id + $stepsCount) % $passwordLength;
            } else {
                $newId = $id - $stepsCount;
                if ($newId < 0) {
                    $newId += $passwordLength; // lets's hope steps count isn't > passwordLength
                }
            }
            $newPassword[$newId] = $letter;
        }
        ksort($newPassword);
        $password = $newPassword;
    }
    elseif (preg_match("/rotate based on position of letter ([a-z])/", $instruction, $matches) === 1) {
        /* for this one, the idea is to find the step count based on the current position of the letter
        we know that  curPos = (oldPos + (oldPos + 1)) % length   (or oldPos + 2 if oldPos >= 4)
        without the modulo:   curPos = oldPos * 2 + 1    OR   curPos = oldPos * 2 + 2 - length     when (oldPos*2 + 1) >= (length)  OR even   curPos = oldPos * 2 + 2 - length * 2  when oldPos * 2 + 2 > length * 2
        so                    oldPos = (curPos - 1) / 2  OR   oldPos = (curPos - 2 + length) / 2   OR   oldPos = (curPos - 2 + length * 2) / 2    when curPos - 2 < 0

        One problem is detecting/deciding when the old id was actually < or > to 4
        when observing the new id of each old id, we can see that new id is odd when old id is < 4 (new id is even otherwise)
         old id     new id (curPos)  (inspired by a reddit post)
        a_______   _a______
        _a______   ___a____
        __a_____   _____a__
        ___a____   _______a
        ____a___   __a_____
        _____a__   ____a___
        ______a_   ______a_
        _______a   a_______

        when curPos is even:   oldPos = (curPos - 2 + length) / 2
                            OR oldPos = (curPos - 2 + length * 2) / 2    when curPos - 2 < 0
        when curPos is odd:    oldPos = (curPos - 1) / 2
        */
        $passwordLength = count($password);
        $curPos = array_search($matches[1], $password);
        $oldPos = -1;
        if ($curPos % 2 === 0) {
            $_curPos = $curPos;
            $_curPos -= 2;
            if ($_curPos < 0) {
                $_curPos += $passwordLength;
            }
            $_curPos += $passwordLength;
            $oldPos = $_curPos / 2;
        } else {
            $oldPos = ($curPos - 1) / 2;
        }

        $stepsCount = $curPos - $oldPos;
        if ($stepsCount !== 0) {
            $newPassword = [];
            foreach ($password as $id => $letter) {
                if ($stepsCount < 0) { // rotate left
                    $newId = ($id + abs($stepsCount)) % $passwordLength;
                } elseif ($stepsCount > 0) { // rotate right
                    $newId = $id - $stepsCount;
                    if ($newId < 0) {
                        $newId += $passwordLength; // lets's hope steps count isn't > passwordLength
                    }
                }
                $newPassword[$newId] = $letter;
            }
            ksort($newPassword);
            $password = $newPassword;
        }
    }
    elseif (preg_match("/reverse positions ([0-9]+) through ([0-9]+)/", $instruction, $matches) === 1) {
        // nothing to change here
        $pos1 = (int)$matches[1];
        $pos2 = (int)$matches[2];
        $reversed = array_splice($password, $pos1, ($pos2 - $pos1) + 1);
        $reversed = array_reverse($reversed);
        array_splice($password, $pos1, 0, $reversed);

    }
    elseif (preg_match("/move position ([0-9]+) to position ([0-9]+)/", $instruction, $matches) === 1) {
        $pos1 = (int)$matches[2]; // jus change the positions
        $pos2 = (int)$matches[1];
        $letter = array_splice($password, $pos1, 1); // actually an array with 1 letter
        array_splice($password, $pos2, 0, $letter);
    }
}

$password = implode("", $password);
echo "Day 21.2: $password\n";
