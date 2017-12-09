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
        foreach ($this->children as $child) {
            $weight = $child->getTotalWeight();
            $weightsPerChildName[$child->name] = $weight;
            $totalWeight += $weight;
        }

        // check if balanced
        /*$countPerWeights = array_count_values($weightsPerChildName);
        if (count($countPerWeights) === 2) { // there is only one unbalanced program, so in that case there is only two different weights
            $weights = []; // first entry is the unbalanced one, value is name and weight
            foreach ($weightsPerChildName as $name => $weight) {
                if (empty($weights)) {
                    $weights[] = [
                        "name" => $name,
                        "weight" => $weight,
                    ];
                } else {
                    if ($weight !== $weights[0]["weight"]) {
                        $weights[] = [
                            "name" => $name,
                            "weight" => $weight,
                        ];
                    }
                    if ($weight === $weights[0]["weight"]) {
                        // weight 1 is the unbalanced one

                    }
                }
            }

            $uniqueWeightsPerChildName = array_unique($weightsPerChildName);
            $childNamePerWeight = array_flip($uniqueWeightsPerChildName);

            ksort($countPerWeights); // sort by weights
            $counts = array_values($countPerWeights);
            $unbalancedName = $childNamePerWeight[$counts[0]];
        }
        */
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