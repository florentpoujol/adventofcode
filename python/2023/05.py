import time

import utils

# Day 2023.05

f = open("inputs/05.txt", "r")
lines = f.readlines()

# test input
# lines: list[str] = """seeds: 79 14 55 13
#
# seed-to-soil map:
# 50 98 2
# 52 50 48
#
# soil-to-fertilizer map:
# 0 15 37
# 37 52 2
# 39 0 15
#
# fertilizer-to-water map:
# 49 53 8
# 0 11 42
# 42 0 7
# 57 7 4
#
# water-to-light map:
# 88 18 7
# 18 25 70
#
# light-to-temperature map:
# 45 77 23
# 81 45 19
# 68 64 13
#
# temperature-to-humidity map:
# 0 69 1
# 1 0 69
#
# humidity-to-location map:
# 60 56 37
# 56 93 4""".splitlines()


for i, line in enumerate(lines):
    lines[i] = line.replace("\n", "")

# --------------------------------------------------
# init

utils.startTimer()

def strListToIntList(line: str) -> list[int]:
    return list(map(int, line.split(' ')))

class RangeClass: # Range conflict with the range() global construct needed for part 2
    mapName: str
    destStart: int = 0
    sourceStart: int = 0
    length: int = 0

    def __init__(self, mapName: str, destStart: int, sourceStart: int, length: int):
        self.mapName = mapName
        self.destStart = destStart
        self.sourceStart = sourceStart
        self.length = length

    def isInRange(self, value: int) -> bool:
        return value >= self.sourceStart and value <= self.sourceStart + self.length - 1

    def getDestination(self, value: int) -> int:
        offset: int = value - self.sourceStart
        return self.destStart + offset

    # methods for part2
    def isInDestinationRange(self, value: int) -> bool:
        return value >= self.destStart and value <= self.destStart + self.length - 1

    def getSource(self, value: int) -> int:
        offset: int = value - self.destStart
        return self.sourceStart + offset

    def __str__(self):
        return f"Range [{self.mapName} dest={self.destStart} source={self.sourceStart} len={self.length}]"
# end class

seeds: list[int] = []
maps: dict[str, list[RangeClass]] = {}
mapName: str = ''

for line in lines:
    if line.startswith("seeds: "):
        seeds = strListToIntList(line.replace("seeds: ", ""))
        continue

    if line == '': continue

    if 'map:' in line:
        mapName: str = line.replace(' map:', '')
        maps[mapName] = []
        currentMap = maps[mapName]
        continue

    # the line is a range
    numbers = strListToIntList(line)
    maps[mapName].append(RangeClass(mapName, numbers[0], numbers[1], numbers[2]))

utils.endInit()


# --------------------------------------------------
# part 1

checkOrder: list[str] = [
    'seed-to-soil',
    'soil-to-fertilizer',
    'fertilizer-to-water',
    'water-to-light',
    'light-to-temperature',
    'temperature-to-humidity',
    'humidity-to-location',
]

utils.startTimer()

lowestLocation: int = -1
for seed in seeds:
    # get location for seed
    valueToCheck: int = seed

    for mapName in checkOrder:
        for rangeObject in maps[mapName]:
            if rangeObject.isInRange(valueToCheck):
                valueToCheck = rangeObject.getDestination(valueToCheck)
                break

    # by now valueToCheck must be a location

    if lowestLocation == -1:
        lowestLocation = valueToCheck
    elif valueToCheck < lowestLocation:
        lowestLocation = valueToCheck
# end for

utils.endPart('1', lowestLocation) # 1181555926 in 0.28ms


# --------------------------------------------------
# part 2

utils.startTimer()

# Same also as before but run a bazillion (probably) more times.
# It is much too slow, it has checked only 307 millions seeds in about one hour (85K per seconds)
# lowestLocation: int = 999_999_999_999
# checkedSeedCount: int = 0
# for i in range(0, int(len(seeds)  / 2 + 1), 2):
#     seedStart = seeds[i]
#     seedRange = seeds[i + 1]
#
#     for seed in range(seedStart, seedStart + seedRange):
#         checkedSeedCount += 1
#         # get location for seed
#         valueToCheck: int = seed
#
#         for mapName in checkOrder:
#             for rangeObject in maps[mapName]:
#                 if rangeObject.isInRange(valueToCheck):
#                     valueToCheck = rangeObject.getDestination(valueToCheck)
#                     break
#
#         # by now valueToCheck must be a location
#
#         if valueToCheck < lowestLocation:
#             lowestLocation = valueToCheck
#
#         if checkedSeedCount % 1_000_000 == 0:
#             print("checked seed count: ", checkedSeedCount, time.time())
# end for

# So instead of going from the seeds to the locations, we do the opposite.
# We check every location from 1 up to the first one that gives one of our starting seeds

checkOrder.reverse()
location: int = 37_800_000

while True:
    location += 1
    if location % 100_000 == 0:
        print(location, time.time())

    valueToCheck = location
    for mapName in checkOrder:
        for rangeObject in maps[mapName]:
            if rangeObject.isInDestinationRange(valueToCheck):
                valueToCheck = rangeObject.getSource(valueToCheck)
                break # go to next map

    # if we are here, we reached a seed,
    # but we still need to check that the seed is indeed within our seeds ranges
    seedRangeFound: bool = False
    for i in range(0, int(len(seeds)  / 2 + 1), 2):
        seedStart = seeds[i]
        seedLength = seeds[i + 1]

        if seedStart <= valueToCheck <= seedStart + seedLength - 1:
            seedRangeFound = True
            break

    if seedRangeFound:
        break
# end while

utils.endPart('2', location) # 37806486
# it took 577s or 9.6 minutes
# the PHP script took 669s/11m
