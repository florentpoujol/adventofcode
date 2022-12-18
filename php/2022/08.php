<?php

declare(strict_types=1);

namespace FlorentPoujoul\Adv2022\_08;

require_once 'tools.php';

$handle = fopen('08_input.txt', 'r');

final class Tree
{
    public ?self $up = null;
    public ?self $down = null;
    public ?self $left = null;
    public ?self $right = null;

    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $height,
        bool $isVisible = null,
    ) {
        if ($isVisible !== null) {
            $this->isVisible = $isVisible;
            $this->visibleChecked = true;
        }
    }

    private bool $visibleChecked = false;
    private bool $isVisible = false;

    public function isVisible(): bool
    {
        if (! $this->visibleChecked) {
            $this->checkVisible();
        }

        return $this->isVisible;
    }

    public function checkVisible(): void
    {
        if ($this->isEdge()) {
            $this->isVisible = true;
            $this->visibleChecked = true;

            return;
        }

        // from any tree, to know if it is visible, you have to go up, down, left and right
        // and check if every trees along are shorter than itself

        foreach (['up', 'down', 'left', 'right'] as $direction) {
            if ($this->{$direction} === null) { // should not happen, means is an edge
                $this->isVisible = true;
                $this->visibleChecked = true;

                continue;
            }

            $referenceTree = $this;

            while ($referenceTree->{$direction} !== null) {
                /** @var self $otherTree */
                $otherTree = $referenceTree->{$direction};

                if ($otherTree->height >= $this->height) {
                    // this tree is not visible FROM this direction
                    break;
                }

                if ($otherTree->{$direction} === null) {
                    // $otherTree is the edge
                    $this->isVisible = true;
                    $this->visibleChecked = true;

                    return;
                }

                $referenceTree = $otherTree;
            }
        }
    }

    public function isEdge(): bool
    {
        return
            $this->up === null
            || $this->down === null
            || $this->right === null
            || $this->left === null;
    }

    /** @var array<array<self>> */
    public static array $grid = [];

    public static function addTree(int $x, int $y, int $height, bool $isVisible = null): void
    {
        $tree = new self($x, $y, $height, $isVisible);

        $tree->up = self::getTreeAt($x, $y - 1);
        if ($tree->up !== null) {
            $tree->up->down = $tree;
        }

        $tree->left = self::getTreeAt($x - 1, $y);
        if ($tree->left !== null) {
            $tree->left->right = $tree;
        }

        self::$grid[$y] ??= [];
        self::$grid[$y][$x] = $tree;
    }

    public static function getTreeAt(int $x, int $y): ?Tree
    {
        return self::$grid[$y][$x] ?? null;
    }

    /**
     * @param callable(int $x, int $y, self $tree): void $callable
     */
    public static function traverse(callable $callable): void
    {
        foreach (self::$grid as $y => $line) {
            foreach ($line as $x => $tree) {
                $callable($x, $y, $tree);
            }
        }
    }

    public static function print(bool $printVisibility = false): void
    {
        foreach (self::$grid as $y => $line) {
            foreach ($line as $x => $tree) {
                if ($printVisibility) {
                    echo $tree->isVisible() ? 'v' : 'i';
                } else {
                    echo $tree->height;
                }
            }

            echo PHP_EOL;
        }
    }

    public function __toString(): string
    {
        $visibility = $this->isVisible() ? 'v' : 'i';

        return "{$this->x}_{$this->y}_{$this->height}_$visibility";
    }

    public static function debug(bool $printVisibility = false): void
    {
        foreach (self::$grid as $y => $line) {
            foreach ($line as $x => $tree) {
                echo ((string) $tree) . ' ';
            }

            echo PHP_EOL;
        }
    }
}

startTimer();

$y = 0;
while (($line = trim((string) fgets($handle))) !== '') {
    $treeHeights = str_split($line);

    $maxX = count($treeHeights) - 1;

    $x = 0;
    foreach ($treeHeights as $height) {
        $isVisible = $x === 0 || $y === 0 || $x === $maxX;
        Tree::addTree($x, $y, (int) $height, $isVisible ? true : null); // important to pass null instead of false when we don't know

        $x++;
    }

    $y++;
}
// the last line is not set as visible, since we can't know in advance that we are on the last line, this is OK

$visibleCount = 0;

Tree::traverse(static function (int $x, int $y, Tree $tree) use(&$visibleCount): void { // REFERENCE($visibleCount)
    if ($tree->isVisible()) {
        $visibleCount++;
    }
});

// Tree::print();
// Tree::print(true);

// Tree::debug();


printDay("08.1 : $visibleCount");

// --------------------------------------------------

startTimer();

$maxScenicScore = 0;

Tree::traverse(static function (int $x, int $y, Tree $tree) use(&$maxScenicScore): void { // REFERENCE($maxScenicScore)
    if ($tree->isEdge()) {
        // edge trees have a scenic score of 0
        return;
    }

    $treeScenicScore = 1;

    foreach (['up', 'down', 'left', 'right'] as $direction) {
        $directionScenicScore = 0;
        $referenceTree = $tree;

        while ($referenceTree->{$direction} !== null) {
            /** @var Tree $otherTree */
            $otherTree = $referenceTree->{$direction};
            $directionScenicScore++;

            if ($otherTree->height >= $tree->height) {
                break;
            }

            $referenceTree = $otherTree;
        }

        $treeScenicScore *= $directionScenicScore;
    }

    $maxScenicScore = max($treeScenicScore, $maxScenicScore);
});

printDay("08.2 : $maxScenicScore");
