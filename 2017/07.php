<?php

class Program
{
    public $name = "";
    public $weight = -1;
    /**
     * @var Program[]
     */
    public $children = [];
    /**
     * @var Program
     */
    public $parent = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getTotalWeight(): int
    {
        $totalWeight = $this->weight;
        $weightsPerChildName = [];
        $firstWeight = -1;
        $firstName = "";
        $otherWeight = -1;
        $otherName = "";
        $unbalancedWeight = -1;
        $balancedWeight = -1;
        $balancedName = "";
        $unbalancedName = "";

        foreach ($this->children as $child) {
            $weight = $child->getTotalWeight();
            $weightsPerChildName[$child->name] = $weight;
            $totalWeight += $weight;

            if ($firstWeight === -1) {
                $firstWeight = $weight;
                $firstName = $child->name;
            } elseif ($otherWeight === -1) {
                if ($weight !== $firstWeight) {
                    $otherWeight = $weight;
                    $otherName = $child->name;
                }
            } else {
                // so this is a third (at least) weight
                // which is equal to either first or other weight
                // because we know that there is only a single unbalanced program

                if ($weight === $firstWeight) {
                    $unbalancedWeight = $otherWeight;
                    $unbalancedName = $otherName;
                } elseif ($weight === $otherWeight) {
                    $unbalancedWeight = $firstWeight;
                    $unbalancedName = $firstName;
                }
                $balancedWeight = $weight;
                $balancedName = $child->name;
            }
        }

        if ($otherName !== "" && $unbalancedName === "") {
            // we registered 2 different weights
            // but the unbalanced variables are not set.
            // that's because otherWeight was the weight of the last children
            // which is also the unbalanced one
            $unbalancedWeight = $otherWeight;
            $unbalancedName = $otherName;
            $balancedWeight = $firstWeight;
            $balancedName = $firstName;
        }

        if ($unbalancedName !== "") {
            // we need the new weight of the unbalanced program
            // $balancedWeight is the total balanced weight
            $diff = $balancedWeight - $unbalancedWeight;
            global $programsPerName;
            var_dump($balancedWeight, $unbalancedWeight, $unbalancedName, $diff, $programsPerName[$unbalancedName]->weight);
            $GLOBALS["balancedWeight"] = $programsPerName[$unbalancedName]->weight + $diff;
            // super hacky AND doesn't work...
        }

        return $totalWeight;
    }
}

$tree = [];
$programsPerName = [];


$resource = fopen("07_input.txt", "r");
//$resource = fopen("07_input_test.txt", "r");

while (($line = fgets($resource)) !== false) {
    $matches = [];
    $pattern = "/(?<name>[a-z]+) \((?<weight>[0-9]+)\)(?: -> (?<children>[a-z, ]+))?/";
    preg_match($pattern, $line, $matches);

    $name = $matches["name"];
    $program = $programsPerName[$name] ?? new Program($name);
    $program->weight = (int)$matches["weight"];
    $programsPerName[$name] = $program;

    if (isset($matches["children"])) {
        $childNames = explode(", ", $matches["children"]);

        foreach ($childNames as $name) {
            $child = $programsPerName[$name] ?? new Program($name);
            $child->parent = $program;
            $programsPerName[$child->name] = $child;
            $program->children[] = $child;
        }
    }
}

// find program without parent (the root)
$rootName = "";
foreach ($programsPerName as $name => $program) {
    if ($program->parent === null) {
        $rootName = $name;
        break;
    }
}

echo "Day 7.1: $rootName\n";

// day 7.2
// find the first program for which its children are unbalanced
// use BFS from a leaf

// go from root, explore every children recursively

$root = $programsPerName[$rootName];

$root->getTotalWeight();
$balancedWeight = $GLOBALS["balancedWeight"] ?? "error";
echo "Day 7.2: $balancedWeight\n";
