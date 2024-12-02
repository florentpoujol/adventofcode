
import * as utils from "./utils.js"
import {input} from "./inputs/02.js"
let strReports = input

// Day 2024.02

// test input
// strReports =
// `7 6 4 2 1
// 1 2 7 8 9
// 9 7 6 2 1
// 1 3 2 4 5
// 8 6 4 4 1
// 1 3 6 7 9`

// --------------------------------------------------
// init

utils.startTimer()

/** @type Array<Array<number>> */
let reports = []

for (const line of strReports.split('\n')) {
    reports.push(
        line.split(' ').map(n => parseInt(n))
    )
}

/**
 * @param {Array<number>} report
 *
 * @return {number} -1 if the report is safe, the index of the first unsafe level if the report is unsafe
 */
function getFirstUnsafeLevelIndex(report)
{
    let lastLevel = -1
    let increasing = undefined // true or false (decreasing)

    for (const index in report) {
        const level = report[index]
        if (lastLevel === -1) {
            lastLevel = level
            continue
        }

        if (increasing === undefined) {
            if (level === lastLevel) {
                return index
            }

            increasing = level > lastLevel
        }

        if (
            (increasing && level <= lastLevel) ||
            (!increasing && level >= lastLevel) ||
            Math.abs(level - lastLevel) > 3
        ) {
            return index
        }

        lastLevel = level
    }

    return -1
}

/**
 * @param {Array<number>} report
 *
 * @return {boolean}
 */
function isSafe(report)
{
    return getFirstUnsafeLevelIndex(report) === -1
}

utils.endInit()


// --------------------------------------------------
// part 1
utils.startTimer()

let safeCount = 0
for (const report of reports) {
    if (isSafe(report)) {
        safeCount++
    }
}

utils.endDay('02.1', safeCount) // 257


// --------------------------------------------------
// part 2

utils.startTimer()

safeCount = 0
for (const report of reports) {
    const levelIndex = getFirstUnsafeLevelIndex(report)
    if (levelIndex === -1) {
        safeCount++
        continue
    }

    // If we are here, the report is unsafe because of at least one level.

    // Try again with either the levelIndex, or the previous levelIndex removed from the report.
    // If either one of these are also unsafe, the report can not be safe.

    if (
        isSafe(report.toSpliced(0, 1)) // this additional check is actually needed to give the correct answer, I'm not sure why
        || (levelIndex >= 2 && isSafe(report.toSpliced(levelIndex - 1, 1))) // >= 2 instead of (>= 1) because we are always checking 0 above
        || isSafe(report.toSpliced(levelIndex, 1))
    ) {
        safeCount++
    }

    // this is the brute-force, which isn't even really slow
    // for (let i = 0; i < report.length; i++) {
    //     if (isSafe(report.toSpliced(i, 1))) {
    //         safeCount++
    //         break
    //     }
    // }
}

utils.endDay('02.2', safeCount) // 328
