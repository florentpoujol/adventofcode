
import * as utils from "./utils.js"
import {input} from "./inputs/03.js"
let data = input

// Day 2024.03

// test input
// data = `xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))`

// --------------------------------------------------
// init

/**
 * @param {IterableIterator<RegExpExecArray>} regexIterator
 * @return number
 */
function multiply(regexIterator)
{
    return [...regexIterator]
        .reduce(
            (acc, entries) => acc + parseInt(entries[1]) * parseInt(entries[2]),
            0 // start
        )
}

// --------------------------------------------------
// part 1

utils.startTimer()

const findMulRegex = new RegExp(/mul\((\d{1,3}),(\d{1,3})\)/, 'g')

let total = multiply(data.matchAll(findMulRegex))

utils.endPart('1', total) // 191183308


// --------------------------------------------------
// part 2

utils.startTimer()

let enabled = true
let searchStartIndex = 0
const dataLength = data.length
total = 0

let i = 0
do {
    if (enabled) {
        // when enabled find the next place where it's disabled
        let oldSearchStartIndex = searchStartIndex

        let nextDisableIndex = data.indexOf("don't()", searchStartIndex)
        if (nextDisableIndex !== -1) {
            searchStartIndex = nextDisableIndex + 7 // +7 removes "don't()"
            enabled = false
        } else {
            searchStartIndex = dataLength // only happen at the end
        }

        // run the regex with the string between the two
        let matches = data
            .substring(oldSearchStartIndex, searchStartIndex)
            .matchAll(findMulRegex)

        total += multiply(matches)
    }

    if (!enabled) {
        // when disabled, find the next place where it's enabled and ignore the string between
        let nextEnableIndex = data.indexOf('do()', searchStartIndex)
        if (nextEnableIndex !== -1) {
            searchStartIndex = nextEnableIndex + 4 // +4 removes "do()"
            enabled = true
        } else {
            break // nothing to do until the end
        }
    }
} while (searchStartIndex < dataLength && ++i < 1_00_000)

utils.endPart('2', total) // 92082041
