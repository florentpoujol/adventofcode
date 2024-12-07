
import * as utils from "./utils.js"
import {input} from "./inputs/07.js"
let inputStr = input

// Day 2024.06

// test input
// inputStr =
// `190: 10 19
// 3267: 81 40 27
// 83: 17 5
// 156: 15 6
// 7290: 6 8 6 15
// 161011: 16 10 13
// 192: 17 8 14
// 21037: 9 7 18 13
// 292: 11 6 16 20`


// --------------------------------------------------
// init

utils.startTimer()

/** @type {Object<string, array<number>>} */
const data = {}

for (const line of inputStr.split('\n')) {
    const numbers = line.split(':')
    data[numbers[0]] = numbers[1].trim().split(' ').map(n => parseInt(n))
}

utils.endInit()


// --------------------------------------------------
// part 1

utils.startTimer()

let operationCount = 0
let totalSum = 0
for (const [testValueStr, numbers] of Object.entries(data)) {
    const testValue = parseInt(testValueStr)

    /** @type {array<string>} */
    let operations = new Array(numbers.length - 1)
    operations = operations.fill('*')

    let isAllStars = true
    let firstOperation = true
    const containOnes = numbers.includes(1)
    do {
        let result = numbers[0]
        for (let i in operations) {
            i = parseInt(i) // please kill me...
            const operation = operations[i]
            if (operation === '*') {
                result *= numbers[i + 1]
            } else {
                result += numbers[i + 1]
            }

            operationCount++
        }

        if (result === testValue) {
            totalSum += testValue
            break
        }

        // break early when we know we can
        if (firstOperation && !containOnes && result < testValue) {
            break
        }
        firstOperation = false

        // "increment" operations
        for (let i = operations.length - 1; i >= 0; i--) {
            if (operations[i] === '*') { // 0
                operations[i] = '+' // 1
                break
            } else {
                operations[i] = '*'
                // we don't break here so that we will change the operator in the next i
            }
        }

        isAllStars = true
        for (const operation of operations) {
            if (operation === '+') {
                isAllStars = false
                break
            }
        }
    } while (!isAllStars)
    // if operations is all * again, we didn't find a matching set of operators
}

utils.dump("part 1 operationCount", operationCount)
utils.endPart('1', totalSum) // 1985268524462 (2_135_062 operations done in 118 ms)
// with the break early on the first operation, it reduces the number of operations to 1_956_250 and the runtime to 105ms

// --------------------------------------------------
// part 2

utils.startTimer()

operationCount = 0
totalSum = 0

for (const [testValueStr, numbers] of Object.entries(data)) {
    const testValue = parseInt(testValueStr)

    /** @type {array<string>} */
    let operations = new Array(numbers.length - 1)
    operations = operations.fill('*')

    let isAllStars = true
    do {
        let result = numbers[0]
        let concatenatedNumbers = Array.from(numbers)

        for (let i in operations) {
            i = parseInt(i) // please kill me...
            const operation = operations[i]
            if (operation === '*') {
                result *= concatenatedNumbers[i + 1]
            } else if (operation === '+') {
                result += concatenatedNumbers[i + 1]
            } else { // "||" operator
                result = parseInt(`${result}${concatenatedNumbers[i + 1]}`)
            }

            operationCount++
        }

        if (result === testValue) {
            totalSum += testValue
            break
        }

        // "increment" operations
        for (let i = operations.length - 1; i >= 0; i--) {
            if (operations[i] === '*') { // 0
                operations[i] = '+' // 1
                break
            } else if (operations[i] === '+') {
                operations[i] = '|'
                break
            } else {
                operations[i] = '*'
                // we don't break here so that we will change the operator in the next i
            }
        }

        isAllStars = true
        for (const operation of operations) {
            if (operation !== '*') {
                isAllStars = false
                break
            }
        }
    } while (!isAllStars)
    // if operations is all * again, we didn't find a matching set of operators
}

utils.dump("part 2 operationCount", operationCount)
utils.endPart('2', totalSum) // 150077710195188 (102_172_828 operations done in 9 s)
