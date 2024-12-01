
import * as utils from "./utils.js";
import {input} from "./inputs/01.js";
let lists = input

// Day 2024.01

// test input
// lists =
// `3   4
// 4   3
// 2   5
// 1   3
// 3   9
// 3   3`


// init

/** @type Array<number> */
let left = []
/** @type Array<number> */
let right = []

for (const line of lists.split('\n')) {
    const numbers = line.split('   ')
    left.push(parseInt(numbers[0]))
    right.push(parseInt(numbers[1]))
}

left.sort((a, b) => a > b)
right.sort((a, b) => a > b)

// --------------------------------------------------
// part 1

utils.startTimer()

let distance = 0
for (const [index, leftValue] of Object.entries(left)) {
    distance += (Math.abs(right[index] - leftValue))
}

utils.endDay('01.1', distance) // 2031679

// --------------------------------------------------
// part 2

utils.startTimer()

let totalSimScore = 0
let count = 0
let lastLeftValue = undefined

// for technique 3 (is not faster than technique 1)
// let countPerNumber = []
// for (const value of right) {
//     countPerNumber[value] ??= 0
//     countPerNumber[value]++
// }

for (const leftValue of left) {
    if (leftValue !== lastLeftValue) {
        lastLeftValue = leftValue
        count = 0

        // technique 1
        // let rightValue = 0
        // do {
        //     rightValue = right.shift();
        //     if (rightValue === leftValue) {
        //         count++;
        //     }
        // } while (rightValue <= leftValue)
        // right.unshift(rightValue)

        // technique 2
        // This bit below is the same but is slower, it takes 8/11 ms, against 0 ms
        // I thought not touching the array with shift/unshift would be faster,
        // but I guess the advantage of reducing the array size every time is worth it.
        for (const value of right) {
            if (value > leftValue) { // this works because the array is sorted asc
                break;
            }
            if (value === leftValue) {
                count++
            }
        }
    }

    // count = countPerNumber[leftValue] ?? 0 // for technique 3 (the whole if above is no useful then)
    totalSimScore += leftValue * count;
}

utils.endDay('01.2', totalSimScore) // 19678534


























