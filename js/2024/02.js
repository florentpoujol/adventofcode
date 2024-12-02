
import * as utils from "./utils.js"
import {input} from "./inputs/02.js"
let strReports = input

// Day 2024.02

// test input
strReports =
`7 6 4 2 1
1 2 7 8 9
9 7 6 2 1
1 3 2 4 5
8 6 4 4 1
1 3 6 7 9`

// --------------------------------------------------
// init

/** @type Array<Array<number>> */
let reports = []

for (const line of strReports.split('\n')) {
    reports.push(
        line.split(' ').map(n => parseInt(n))
    )
}

// --------------------------------------------------
// part 1

utils.startTimer()

let safeCount = 0
for (const report of reports) {
    let lastLevel = -1
    let increasing = undefined // true or false (decreasing)
    let safe = true

    for (const level of report) {
        if (lastLevel === -1) {
            lastLevel = level
            continue
        }

        if (increasing === undefined) {
            if (level === lastLevel) {
                safe = false
                break
            }

            increasing = level > lastLevel
        }

        if (
            (increasing && level <= lastLevel) ||
            (!increasing && level >= lastLevel) ||
            Math.abs(level - lastLevel) > 3
        ) {
            safe = false
            break
        }

        lastLevel = level
    }

    if (safe) {
        safeCount++
    }
}

utils.endDay('02.1', safeCount) // 257

// --------------------------------------------------
// part 2

utils.startTimer()


/*safeCount = 0
for (const report of reports) {
    let lastLevel = -1
    let increasing = undefined // true or false (decreasing)
    let safe = true
    let problemDamped = false
    /!** @type {Array<int|string>} *!/
    let dampedData = []
    let lastLevelIsFirstLevel = true // only true for the second loop

    for (let level of report) {
        if (lastLevel === -1) {
            lastLevel = level
            continue
        }

        if (increasing === undefined) {
            if (level === lastLevel) {
                // utils.dd("level === lastLevel", report, level, lastLevel)
                if (problemDamped) {
                    dampedData.push('check increasing not safe', level)

                    safe = false
                    break
                } else {
                    problemDamped = true
                    dampedData = [level, lastLevel, 'check increasing']

                    // here we must just ignore the previous level
                    lastLevel = level
                    continue
                }
            }

            increasing = level > lastLevel
        }

        if (
            (increasing && level <= lastLevel) ||
            (!increasing && level >= lastLevel) ||
            Math.abs(level - lastLevel) > 3
        ) {
            if (problemDamped) {
                dampedData.push('main not safe', level)
                safe = false
                break
            } else {
                problemDamped = true
                dampedData = [level, lastLevel]

                if (lastLevelIsFirstLevel) {
                    // if we are on the second level, we must ignore the first level, not the current level
                    lastLevel = level
                    increasing = undefined
                    dampedData.push('ignore previous level')
                    continue
                }

                // this is key
                // in part 2 we must not ignore unsafe *transitions*, but unsafe levels (numbers)
                // so here I make so to ignore the current level
                // so that the next level is compared with the previous level
                // (the lastLevel stays the same after this loop)
                if (increasing) {
                    lastLevel = level
                } else {
                    level = lastLevel
                }
            }
        }

        lastLevel = level

        if (lastLevelIsFirstLevel) {
            lastLevelIsFirstLevel = false
        }
    }

    if (!safe) {
        utils.dump(report, ...dampedData)
    }

    if (safe) {
        safeCount++
    }
}*/


safeCount = 0
for (const report of reports) {
    let deleteIndex = 0

    innerLoop:
    let currentReport = [...report]
    let problemDamped = false

    let safe = true

    // this below is really dumb performance-wise, but its "simple" to understand
    const reportStr = currentReport.toString()
    const sortedReportAsc = [...currentReport].sort((a, b)=> a < b)
    const sortedReportDesc = [...currentReport].sort((a, b)=> a > b)

    if (reportStr !== sortedReportAsc.toString() && reportStr !== sortedReportDesc.toString()) {
        safe = false
    }

    // now just check the diff between each subsequent numbers
    let lastLevel = -1
    for (let level of currentReport) {
        if (!safe) {
            break
        }

        if (lastLevel === -1) {
            lastLevel = level;
            continue;
        }

        const diff = Math.abs(level - lastLevel)
        if (diff < 1 || diff > 3) {
            safe = false
            break
        }

        lastLevel = level
    }

    if (safe) {
        safeCount++
        continue
    }

    // if we are here, we know there is at least one problematic level
    // so try the same report again, but with each levels removed in turn
    currentReport = report.splice(deleteIndex, 1)
    deleteIndex++
}

utils.endDay('02.2', safeCount) // 313 too low
