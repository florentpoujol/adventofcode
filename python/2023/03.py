import json
from traceback import format_list

import utils

# Day 2023.03

f = open("inputs/03.txt", "r")
lines = f.readlines()

# test input
# lines = """467..114..
# ...*......
# ..35..633.
# ......#...
# 617*......
# .....+.58.
# ..592.....
# ......755.
# ...$.*....
# .664.598..""".split("\n")

for i, line in enumerate(lines):
    lines[i] = line.replace("\n", "")


# --------------------------------------------------
# part 1

utils.startTimer()

def checkIfPartNumber(targetX: int, targetY: int) -> bool:
    global lines, numbersPerGear
    maxLineLength: int = len(lines[0])
    maxHeight: int = len(lines)

    for y in range(targetY - 1, targetY + 2):
        if y < 0 or y >= maxHeight:
            continue

        for x in range(targetX - 1, targetX + 2):
            if x < 0 or x >= maxLineLength:
                continue

            if y == targetY and x == targetX:
                continue

            char: str = lines[y][x]
            if char != '.' and not char.isdigit():
                return True

    return False
# end function

currentNumber: str = ''
sum: int = 0
isPartNumber: bool = False

for y, line in enumerate(lines):
    for x, char in enumerate(line):
        if char.isdigit():
            currentNumber += char
            if not isPartNumber:
                isPartNumber = checkIfPartNumber(x, y)
        elif currentNumber != '':
            if isPartNumber:
                sum += int(currentNumber)
                isPartNumber = False
            currentNumber = ''

utils.endPart('1', sum) # 553825


# --------------------------------------------------
# part 2

utils.startTimer()

# keys are the gears coordinates in the for "x_y", values are list of their adjacent numbers
numbersPerGear: dict[str, list[int]] = {}

def checkAdjacentGear(lastDigitX: int, lastDigitY: int, number: int) -> None:
    # note: the coordinates here are the end of the current number
    global lines, numbersPerGear

    maxLineLength: int = len(lines[0])
    maxHeight: int = len(lines)

    # check all digits of the number
    for digitX in range(lastDigitX - len(str(number)) + 1, lastDigitX + 1):
        for y in range(lastDigitY - 1, lastDigitY + 2):
            if y < 0 or y >= maxHeight:
                continue

            for x in range(digitX - 1, digitX + 2):
                if x < 0 or x >= maxLineLength:
                    continue

                if y == lastDigitY and x == digitX:
                    continue

                char: str = lines[y][x]
                if char != '*':
                    continue

                gearCoords: str = f"{x}_{y}"
                if numbersPerGear.get(gearCoords) is None:
                    numbersPerGear[gearCoords] = []

                if number not in numbersPerGear[gearCoords]:
                    numbersPerGear[gearCoords].append(number)
                    return
                    # we make the correct asumption here that no gear is next to two identical number
                    # and that no numbers is next to two gear
# end function

currentNumber: str = ''

for y, line in enumerate(lines):
    if currentNumber != '': # the number ended at the last column
        checkAdjacentGear(len(line) - 1, y - 1, int(currentNumber))
        currentNumber = ''

    for x, char in enumerate(line):
        if char.isdigit():
            currentNumber += char
        elif currentNumber != '':
            checkAdjacentGear(x - 1, y, int(currentNumber))
            currentNumber = ''

sum: int = 0
for gearCoords, numbers in numbersPerGear.items():
    if len(numbers) == 2:
        sum += numbers[0] * numbers[1]

utils.endPart('2', sum) # 93994191
