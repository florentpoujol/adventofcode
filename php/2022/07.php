<?php

declare(strict_types=1);

namespace FlorentPoujol\Adv2022\_07;

use RuntimeException;

require_once 'tools.php';

final class File
{
    public function __construct(
        public readonly string $name,
        public readonly int $size,
    ) {
    }
}

final class Directory
{
    public readonly string $fullName;

    /** @var array<self|File> */
    public array $content = [];

    public int $size = 0;

    public function __construct(
        public readonly string $name,
        public readonly ?self $parent = null,
    ) {
        if ($this->parent === null) {
            $this->fullName = $this->name;
        } else {
            $separator = '/';
            if ($this->parent->name === '/') {
                $separator = '';
            }

            $this->fullName = "{$this->parent->fullName}$separator$this->name";
        }
    }

    public function moveToSubDir(string $name): self
    {
        foreach ($this->content as $child) {
            if ($child instanceof self && $child->name === $name) {
                return $child;
            }
        }

        throw new RuntimeException("Couldn't find sub dir '$name', in '$this->name'");
    }

    public function addChild(string $name): self
    {
        $child = new self($name, $this);
        $this->content[] = $child;

        return $child;
    }

    public function addFile(string $name, int $size): void
    {
        $file = new File($name, $size);
        $this->content[] = $file;

        $this->size += $size;
    }

    public function print(string $indentation = ''): void
    {
        echo "$indentation- $this->name (dir)" . PHP_EOL;
        $indentation .= '  ';

        foreach ($this->content as $item) {
            if ($item instanceof self) {
                $item->print($indentation);
            }
            // else file

            echo "$indentation- $item->name (file, size=$item->size)" . PHP_EOL;
        }
    }

    public function getTotalSize(): int
    {
        $totalSize = $this->size;

        foreach ($this->content as $item) {
            if ($item instanceof self) {
                $totalSize += $item->getTotalSize();
            }
        }

        return $totalSize;
    }

    public function traverse(callable $callable): void
    {
        $callable($this);

        foreach ($this->content as $item) {
            if ($item instanceof self) {
                $item->traverse($callable);

                continue;
            }
            // else file

            $callable($item);
        }
    }
}

$handle = fopen('07_input.txt', 'r');

startTimer();

/** @var null|Directory $currentDir */
$currentDir = new Directory('/');
$root = $currentDir;

while (($line = trim((string) fgets($handle))) !== '') {
    if ($line === '$ ls' || $line === '$ cd /') {
        continue;
    }

    $matches = [];
    if (preg_match('/^\$ cd (.+)$/', $line, $matches)) {
        if ($matches[1] === '..') {
            $currentDir = $currentDir->parent;
            assert($currentDir !== null);

            continue;
        }

        $currentDir = $currentDir->moveToSubDir($matches[1]);

        continue;
    }

    if (preg_match('/^dir (.+)$/', $line, $matches)) {
        $currentDir->addChild($matches[1]);

        continue;
    }

    if (preg_match('/^(\d+) (.+)$/', $line, $matches)) {
        $currentDir->addFile($matches[2], (int) $matches[1]);
    }
}

$part1TotalSize = 0;

$root->traverse(function (Directory|File $item) use (&$part1TotalSize): void { // REFERENCE(part1TotaltSize)
    if ($item instanceof Directory) {
        $dirTotalSize = $item->getTotalSize();
        if ($dirTotalSize > 100_000) {
            return;
        }

        $part1TotalSize += $dirTotalSize;

        // echo "$item->fullName {$item->getTotalSize()}" . PHP_EOL;
    }
});

printDay("07.1 : $part1TotalSize");

// --------------------------------------------------

startTimer();

$rootTotalSize = $root->getTotalSize();
$freeSpace = 70_000_000 - $rootTotalSize;
$spaceToFree = 30_000_000 - $freeSpace;

echo "spaceToFree: $spaceToFree" . PHP_EOL;

$dirSizeToDelete = 70_000_000;

$root->traverse(function (Directory|File $item) use (&$dirSizeToDelete, $spaceToFree): void { // REFERENCE(candidateDirs)
    if ($item instanceof Directory) {
        $dirTotalSize = $item->getTotalSize();
        if ($dirTotalSize > $spaceToFree && $dirTotalSize < $dirSizeToDelete) {
            $dirSizeToDelete = $dirTotalSize;
        }
    }
});

printDay("07.2 : $dirSizeToDelete");
