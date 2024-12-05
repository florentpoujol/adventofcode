
import * as utils from "./utils.js"
import {inputPageOrderingRules, inputPagesToProduce} from "./inputs/05.js"
let pageOrderingRulesStr = inputPageOrderingRules
let pagesToProduceStr = inputPagesToProduce

// Day 2024.05

// test input
// pageOrderingRulesStr =
// `47|53
// 97|13
// 97|61
// 97|47
// 75|29
// 61|13
// 75|53
// 29|13
// 97|29
// 53|29
// 61|53
// 97|53
// 61|29
// 47|13
// 75|47
// 97|75
// 47|61
// 75|61
// 47|29
// 75|13
// 53|13`
//
// pagesToProduceStr =
// `75,47,61,53,29
// 97,61,53,29,13
// 75,29,13
// 75,97,47,61,53
// 61,13,29
// 97,13,75,29,47`

// --------------------------------------------------
// init

utils.startTimer()

/**
 * @type array<array<int>> top level keys are not contiguous, they are the page numbers,
 *  values are arrays of the pages numbers that must come AFTER them
 */
let pageOrderingRules = []
for (const rule of pageOrderingRulesStr.split('\n')) {
    const numbers = rule.split('|')
    pageOrderingRules[parseInt(numbers[0])] ??= []
    pageOrderingRules[parseInt(numbers[0])].push(parseInt(numbers[1]))
}

/** @type array<array<int>> */
let pagesToProduce = []
for (const pages of pagesToProduceStr.split('\n')) {
    pagesToProduce.push(
        pages.split(',').map(u => parseInt(u))
    )
}

utils.endInit()
// utils.dd(pageOrderingRules, pagesToProduce)

// --------------------------------------------------
// part 1

utils.startTimer()

/** @type array<array<int>> */
let incorrectlyOrderedUpdates = []

let sumOfMiddlePageNumber = 0
for (const pages of pagesToProduce) {
    let inTheRightOrder = true

    for (const [pageIndexStr, page] of Object.entries(pages)) {
        const afterPages = pageOrderingRules[page]
        if (afterPages === undefined) {
            continue
        }

        const pageIndex = parseInt(pageIndexStr)

        // afterPages is the list of pages that
        // must come AFTER the current page
        // only IF both are in this update
        for (const afterPage of afterPages) {
            if (!pages.includes(afterPage)) {
                continue
            }

            // both page and afterPage must be printed in the same run
            // check that after page is indeed after page
            if (pages.indexOf(afterPage) < pageIndex) {
                inTheRightOrder = false
                incorrectlyOrderedUpdates.push(pages)
                break
            }
        }

        if (!inTheRightOrder) {
            break
        }
    }

    if (inTheRightOrder) {
        sumOfMiddlePageNumber += pages[Math.floor(pages.length / 2)]
    }
}

utils.endPart('1', sumOfMiddlePageNumber) // 4637


// --------------------------------------------------
// part 2

utils.startTimer()

sumOfMiddlePageNumber = 0

for (const pages of incorrectlyOrderedUpdates) {
    pages.sort(function (a, b) {
        const pagesThatMustBeAfterA = pageOrderingRules[a]
        if (pagesThatMustBeAfterA === undefined) {
            return 0 // no info on ordering
        }

        return pagesThatMustBeAfterA.includes(b) ? 1 : -1
    })

    sumOfMiddlePageNumber += pages[Math.floor(pages.length / 2)]
}

utils.endPart('2', sumOfMiddlePageNumber) // 6370
