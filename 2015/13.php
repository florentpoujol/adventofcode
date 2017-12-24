<?php
// http://adventofcode.com/2015/day/13

$resource = fopen("13_input.txt", "r");

// first lets build a graph
// people are nodes, every other person is a neighbour
// everyone is neighbour to everyone (except itself)
// links between people are weighted (thr sum of how they feel about each other)

// systematically explore every paths from each person
// take in turn each person
// then find its best neighbour (highest weight link leading to non-visited node)
// explore similarly that node

// consider the graph as a tree with each person in turn as the root
// then explore depth-first, markings nodes as visited
// until the chain is 8 person and all the nodes are visited

class Node
{
    /**
     * @var Link[];
     */
    public $links = [];
    public $name;

    public function getLinkTo($name)
    {
        foreach ($this->links as $link) {
            if ($link->node1->name === $name || $link->node2->name === $name) {
                return $link;
            }
        }
        return null;
    }
}

class Link
{
    public $id = "";
    public $weight = 0;
    /**
     * @var Node;
     */
    public $node1;
    /**
     * @var Node;
     */
    public $node2;

    public function __construct($node1, $node2)
    {
        $this->node1 = $node1;
        $this->node2 = $node2;

        $this->id = $node1->name . "_" . $node2->name;

        $node1->links[] = $this;
        $node2->links[] = $this;
    }
}

$nodesPerName = [];
$nodes = [];
$links = [];

while (($line = fgets($resource)) !== false) {
    $line = trim($line);

    $matches = [];
    preg_match("/^([a-z]+) would (lose|gain) ([0-9]+) .+ ([a-z]+)\.$/i", $line, $matches);

    $name = $matches[1];
    $node = null;
    if (! isset($nodesPerName[$name])) {
        $node = new Node();
        $node->name = $name;
        $nodesPerName[$name] = $node;
        $nodes[] = $node;
    } else {
        $node = $nodesPerName[$name];
    }

    $neighbourName = $matches[4];
    $neighbourNode = null;
    if (! isset($nodesPerName[$neighbourName])) {
        $neighbourNode = new Node();
        $neighbourNode->name = $neighbourName;
        $nodesPerName[$neighbourName] = $neighbourNode;
        $nodes[] = $neighbourNode;
    } else {
        $neighbourNode = $nodesPerName[$neighbourName];
    }

    $link = $node->getLinkTo($neighbourName);
    if ($link === null) {
        $link = new Link($node, $neighbourNode);
        $links[] = $link;
    }

    $gain = (int)$matches[3];
    if ($matches[2] === "lose") {
        $gain = 0 - $gain;
    }
    $link->weight += $gain;
}

// recursively loop through every node and neighbours

// loop through every links
// if X link have been used
// return and add thee weigh saved
// save the weight of the selected link in an array
// take node 2
// select a link that lead to an not visited node

$personCount = count(array_keys($nodesPerName));
$maxWeight = -999;

foreach ($nodes as $node) {
    processNode($node, [], []);
}

/**
 * @param Node $node
 * @param Node[] $visitedNodes
 * @param Link[] $usedLinks
 */
function processNode($node, $visitedNodes, $usedLinks)
{
    global $personCount;
    $visitedNodes[] = $node;

    foreach ($node->links as $link) {
        $nextNode = null;
        if (! in_array($link->node1, $visitedNodes)) {
            $nextNode = $link->node1;
        } elseif (! in_array($link->node2, $visitedNodes)) {
            $nextNode = $link->node2;
        }

        if ($nextNode !== null) {
            $usedLinks2 = $usedLinks;
            $usedLinks2[] = $link;

            processNode($nextNode, $visitedNodes, $usedLinks2);
        }
    }

    if (count($visitedNodes) === $personCount) {
        // all links leads to a visited node
        // this node is the last to be seated
        $usedLinks[] = $node->getLinkTo($visitedNodes[0]->name);
        registerWeight($usedLinks);
    }
}


function registerWeight($usedLinks)
{
    global $maxWeight;

    $weight = 0;
    foreach ($usedLinks as $link) {
        $weight += $link->weight;
    }

    if ($weight > $maxWeight) {
        $maxWeight = $weight;
    }
}

echo "Day 13.1: $maxWeight<br>";

// part 2

// add my links and node
$node = new Node();
$node->name = "Florent";
$nodes[] = $node;

$names = array_keys($nodesPerName);
foreach ($names as $name) {
    $link = new Link($node, $nodesPerName[$name]);
    // weight left at zero
}

$personCount = count(array_keys($nodesPerName)) + 1;
$maxWeight = -999;

foreach ($nodes as $node) {
    processNode($node, [], []);
}

echo "Day 13.2: $maxWeight<br>";
