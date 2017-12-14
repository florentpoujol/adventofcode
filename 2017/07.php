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
        $firstWeight = -1; // weight and name of the first child
        $firstProgram = "";
        $otherWeight = -1; // weight and name of the child different than firstWeight
        $otherProgram = "";
        $unbalancedWeight = -1;
        $unbalancedProgram = "";
        $balancedWeight = -1;

        foreach ($this->children as $child) {
            $weight = $child->getTotalWeight();
            $weightsPerChildName[$child->name] = $weight;
            $totalWeight += $weight;

            if ($firstWeight === -1) {
                $firstWeight = $weight;
                $firstProgram = $child->name;
            } elseif ($otherWeight === -1) {
                if ($weight !== $firstWeight) {
                    $otherWeight = $weight;
                    $otherProgram = $child->name;
                }
            } else {
                // - this is the third (at least) child of that program
                // - firstWeight and otherWeight are set
                // - this child's weight is equal to either first or other weight
                //   because we know that there is only a single unbalanced program
                // - I can only assume that the unbalanced program has at least two siblings
                //   otherwise I could tell which weight is the unbalanced one

                if ($weight === $firstWeight) {
                    $unbalancedWeight = $otherWeight;
                    $unbalancedProgram = $otherProgram;
                } elseif ($weight === $otherWeight) {
                    $unbalancedWeight = $firstWeight;
                    $unbalancedProgram = $firstProgram;
                } else {
                    exit("error, weight should be equal to firstWeight or otherWeight");
                }
                $balancedWeight = $weight;

                // this should not change things that this else block can be called
                // with every subsequent children without changing the value of unbalancedWeight
            }
        }

        if (count($this->children) >= 3 && $otherProgram !== "" && $unbalancedProgram === "") {
            // We registered 2 different weights (first and other)
            // but the unbalanced variables are not set.
            // That's because otherWeight was the weight of the last children
            // which is thus also must be the unbalanced one.
            $unbalancedWeight = $otherWeight;
            $unbalancedProgram = $otherProgram;
            $balancedWeight = $firstWeight;
        }

        if ($unbalancedProgram !== "") {
            // we need the new weight of the unbalanced program
            $diff = $balancedWeight - $unbalancedWeight; // bot weight are the total weight
            global $programsPerName;
            // var_dump($balancedWeight, $unbalancedWeight, $unbalancedProgram, $diff, $programsPerName[$unbalancedProgram]->weight);
            $GLOBALS["balancedWeight"] = $programsPerName[$unbalancedProgram]->weight + $diff;

            $totalWeight += $diff; // fiw this program's weight so that it's parent is not itself considered unbalanced
        }

        return $totalWeight;
    }
}

$tree = [];
$programsPerName = [];


$resource = fopen("07_input.txt", "r");
// $resource = fopen("07_input_test.txt", "r");

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

$root = $programsPerName[$rootName];

$root->getTotalWeight();
$balancedWeight = $GLOBALS["balancedWeight"] ?? "error";
echo "Day 7.2: $balancedWeight\n";

// 1614 too hight (dqwocyn, child of tylelk)
