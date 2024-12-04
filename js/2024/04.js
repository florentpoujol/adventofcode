
import * as utils from "./utils.js"
import {input} from "./inputs/04.js"
let data = input

// Day 2024.04

// test input
// data = `MMMSXXMASM
// MSAMXMSMSA
// AMXSXMAAMM
// MSAMASMSMX
// XMASAMXAMM
// XXAMMXXAMA
// SMSMSASXSS
// SAXAMASAAA
// MAMMMXMMMM
// MXMXAXMASX`

/** @type array<string> */
data = data.split('\n')
const lineLength = data[0].length
const lineCount = data.length


// --------------------------------------------------
// part 1

utils.startTimer()

/**
 * @typedef Direction
 * @property {string} label
 * @property {number} x
 * @property {number} y
 */

/**
 * @type {array<Direction>}
 */
const directionOffsets = [
    { label: 'right',       x: 1,  y: 0, },
    { label: 'right-up',    x: 1,  y: -1, },
    { label: 'up',          x: 0,  y: -1, },
    { label: 'up-left',     x: -1, y: -1, },
    { label: 'left',        x: -1, y: 0, },
    { label: 'down-left',   x: -1, y: 1, },
    { label: 'down',        x: 0,  y: 1, },
    { label: 'down-right',  x: 1,  y: 1, },
]

/**
 *
 * @param {number} x
 * @param {number} y
 * @param {Direction} direction
 * @return boolean
 */
function xmasFoundInDirection(x, y, direction)
{
    const lettersToFind = ['M', 'A', 'S'] // not checking for X because this method is only called when x,y is already X
    for (const letter of lettersToFind) {
        x += direction.x
        if (x < 0 || x >= lineLength) {
            return false
        }

        y += direction.y;
        if (y < 0 || y >= lineCount) {
            return false
        }

        if (data[y][x] !== letter) {
            return false
        }
    }

    return true
}

let xmasCount = 0

for (let x = 0; x < lineLength; x++) {
    for (let y = 0; y < lineCount; y++) {
        // find the next X
        if (data[y][x] !== 'X') {
            continue
        }

        // now check in every 8 direction
        for (const direction of directionOffsets) {
            if (xmasFoundInDirection(x, y, direction)) {
                xmasCount++
            }
        }
    }
}

utils.endPart('1', xmasCount) // 2567


// --------------------------------------------------
// part 2

utils.startTimer()

xmasCount = 0

for (let x = 1; x < lineLength - 1; x++) {
    for (let y = 1; y < lineCount - 1; y++) {
        if (data[y][x] !== 'A') {
            continue
        }

        // top-left to bottom-right
        let letter1 = data[y - 1][x - 1]
        let letter2 = data[y + 1][x + 1]

        const diagonalOneOk = (
            letter1 === 'M' && // up-left
            letter2 === 'S' // down-right
        ) || (
            letter1 === 'S' && // up-left
            letter2 === 'M' // down-right
        )

        // top-right to bottom-left
        letter1 = data[y - 1][x + 1]
        letter2 = data[y + 1][x - 1]

        const diagonalTwoOk = (
            letter1 === 'M' && // up-left
            letter2 === 'S' // down-right
        ) || (
            letter1 === 'S' && // up-left
            letter2 === 'M' // down-right
        )

        if (diagonalOneOk && diagonalTwoOk) {
            xmasCount++;
        }
    }
}

utils.endPart('2', xmasCount) // 2029
