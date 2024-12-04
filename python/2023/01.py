import utils

# Day 2023.01

f = open("inputs/01.txt", "r")
lines = f.readlines()

# test input
# lines = """1abc2
# pqr3stu8vwx
# a1b2c3d4e5f
# treb7uchet""".split("\n")

for i, line in enumerate(lines):
    lines[i] = line.replace("\n", "")


# --------------------------------------------------
# part 1

utils.startTimer()

sum: int = 0
for line in lines:
    firstNumber: str = ''
    lastNumber: str = ''

    for letter in line:
        if not letter.isdigit():
            continue

        lastNumber = letter

        if firstNumber == '':
            firstNumber = letter

    # print(line, f"{firstNumber}{lastNumber}")
    sum += int(f"{firstNumber}{lastNumber}")

utils.endPart('1', sum)


# --------------------------------------------------
# part 2

# test input specific to part 2
# lines = """two1nine
# eightwothree
# abcone2threexyz
# xtwone3four
# 4nineeightseven2
# zoneight234
# 7pqrstsixteen""".split("\n")

utils.startTimer()

searchedStrings: list[str] = [
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
]

literalToInt: dict[str, str] = {
    'one' : '1',
    'two' : '2',
    'three' : '3',
    'four' : '4',
    'five' : '5',
    'six' : '6',
    'seven' : '7',
    'eight' : '8',
    'nine' : '9',
}

sum = 0
for line in lines:
    # using a regex could have been simpler...

    firstNumber: str = ''
    minIndex: int = 99999
    for searchedStr in searchedStrings:
        index: int = line.find(searchedStr)
        if -1 < index < minIndex:
            minIndex = index
            firstNumber = literalToInt.get(searchedStr, searchedStr)

    lastNumber: str = ''
    minIndex: int = 99999
    revLine: str = line[::-1]
    for searchedStr in searchedStrings:
        revSearchedStr = searchedStr[::-1]
        index: int = revLine.find(revSearchedStr)
        if -1 < index < minIndex:
            minIndex = index
            lastNumber = literalToInt.get(searchedStr, searchedStr)

    # print(line, f"{firstNumber}{lastNumber}")
    sum += int(f"{firstNumber}{lastNumber}")

utils.endPart('2', sum)
