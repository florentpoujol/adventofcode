<?php
// this is utter garbage, works with the test example but not with real data
// I need to learn about graph and probably use something like BFS
$resource = fopen("13_input.txt", "r");

$neighbourPerGainPerName = []; // key = name    value = sorted assoc array(variation = array of name)
$neighbourGainPerName = []; // key = name    value = sorted assoc array(name = gain)

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    preg_match("/^([a-z]+) would (lose|gain) ([0-9]+) .+ ([a-z]+)\.$/i", $line, $matches);
    $name = $matches[1];
    $gainDirection = $matches[2];
    $gain = (int)$matches[3];
    $neighbour = $matches[4];

    if (! isset($neighbourPerGainPerName[$name])) {
        $neighbourPerGainPerName[$name] = [];
        $neighbourGainPerName[$name] = [];
    }

    if ($gainDirection === "lose") {
        $gain = 0-$gain;
    }

    if (! isset($neighbourPerGainPerName[$name][$gain])) {
        $neighbourPerGainPerName[$name][$gain] = [];
    }
    // I have to use an array as value because George has the same -63 gain for Alice and Franck
    $neighbourPerGainPerName[$name][$gain][] = $neighbour;

    $neighbourGainPerName[$name][$neighbour] = $gain;
}

$gains = [];
$gainsPerNeighbours = []; // key = "neighbour1_neighbour2", value = gain
$nbPerson = count($neighbourPerGainPerName);

$savedSitedNames = [];

foreach ($neighbourPerGainPerName as $initialName => $neighbourPerGain) {

    echo "############################# <br>";
    echo "initial name = $initialName <br>";

    foreach ($neighbourGainPerName[$initialName] as $neighbour => $gainForThatNeighbour) {
        echo "************************************* <br>";
        $name = $initialName;
        echo "name = $name | neighbour = $neighbour <br>";

        $sitedNames = [$initialName];
        $totalGain = 0;
        $bestNeighbour = $neighbour;
        $firstLoop = true;
        $gainVariations = [];
        $saveForLater = false;

        do {
            echo "--------- entering while loop -------------- <br>";
            if (! $firstLoop) {
                $bestNeighbour = "";

                krsort($neighbourPerGain);
                foreach ($neighbourPerGain as $gain => $neighbours) { // namesPerGain is already sorted biggest gain first

                    if (count($neighbours) === 2) {
                        // George has the same -63 gain for Alice and Franck
                        // when booth are available (this happens twice)
                        // wee need to explore both path, with Alice first then Franck first
                    }

                    foreach ($neighbours as $neighbour) {
                        if (! in_array($neighbour, $sitedNames)) {
                            $bestNeighbour = $neighbour;
                            echo "selected bestNeighbour = $bestNeighbour <br>";

                            if (
                                $name === "George" &&
                                $bestNeighbour === "Alice" &&
                                ! in_array("Frank", $sitedNames)
                            ) {
                                $saveForLater = true;
                            }

                            break;
                        }
                    }

                    if ($bestNeighbour !== "") {
                        break;
                    }
                }
                if ($bestNeighbour === "") {
                    var_dump("-----------------", "ERROR: no best neighbour selected", $name, "----------------");
                    break;
                }
            }
            $firstLoop = false;

            echo "================= <br>";
            var_dump($name, $neighbourPerGain, $bestNeighbour);
            $totalGain += $neighbourGainPerName[$name][$bestNeighbour];
            $totalGain += $neighbourGainPerName[$bestNeighbour][$name];
            $gainVariations[] = $neighbourGainPerName[$name][$bestNeighbour];
            $gainVariations[] = $neighbourGainPerName[$bestNeighbour][$name];

            $sitedNames[] = $bestNeighbour;

            $name = $bestNeighbour;
            $neighbourPerGain = $neighbourPerGainPerName[$name];
        } while (count($sitedNames) < $nbPerson);

        echo "//////////////////////////////////////////////////////////////////// <br>";
        var_dump($sitedNames);
        $totalGain += $neighbourGainPerName[$sitedNames[0]][$sitedNames[$nbPerson - 1]];
        $totalGain += $neighbourGainPerName[$sitedNames[$nbPerson - 1]][$sitedNames[0]];
        $gainVariations[] = $neighbourGainPerName[$sitedNames[0]][$sitedNames[$nbPerson - 1]];
        $gainVariations[] = $neighbourGainPerName[$sitedNames[$nbPerson - 1]][$sitedNames[0]];

        var_dump($gainVariations, array_sum($gainVariations));
        $gains[] = $totalGain;
        $gainsPerNeighbours[$sitedNames[0] . "_" . $sitedNames[1]] = $totalGain;
        echo $sitedNames[0] . "_" . $sitedNames[1]. " = ".$totalGain."<br>";

        if ($saveForLater) {
            // this is the two case where it ends with george > alice > franck
            // we also needs to test george > franck > alice
            $savedSitedNames[] = $sitedNames;
            $saveForLater = false;
        }
    }
}

var_dump("--------------------------------------------------------------------------------------------------------------------");
foreach ($savedSitedNames as $sitedNames) {
    // swap alice and franck then recalculate the total gain
    var_dump($sitedNames);
    $sitedNames[6] = "Frank";
    $sitedNames[7] = "Alice";
    $totalGain = 0;

    for ($i=0; $i<8; $i++) {
        $name = $sitedNames[$i];
        echo "$i   $name <br>";
        if ($i < 7) {
            $totalGain += $neighbourGainPerName[$name][$sitedNames[$i+1]];
            $totalGain += $neighbourGainPerName[$sitedNames[$i+1]][$name];
        } else {
            $totalGain += $neighbourGainPerName[$sitedNames[0]][$sitedNames[7]];
            $totalGain += $neighbourGainPerName[$sitedNames[7]][$sitedNames[0]];
        }
    }
    var_dump($totalGain);
    $gainsPerNeighbours["special_".$sitedNames[0] . "_" . $sitedNames[1]] = $totalGain;
}
var_dump("--------------------------------------------------------------------------------------------------------------------");

ksort($gainsPerNeighbours);
var_dump($gainsPerNeighbours);
rsort($gains);
var_dump($gains);

echo "day 13.1: ".$gains[0]."<br>"; // 573 = too low
