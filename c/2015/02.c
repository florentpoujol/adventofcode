// http://adventofcode.com/2015/day/2

#include <stdio.h>
#include "common.c"

int main() 
{
    int arraySize = 0;
    char** input = readInputAsList("02", &arraySize);
    char line[50];

    int i, side1, side2, side3, tmp, area1, area2, area3, surface = 0, ribbonLength = 0;
    
    for (i = 0; i < arraySize; i++) {
        char* line = input[i];
        char** numbers = strSplit2(line, 'x');
        
        side1 = atoi(numbers[0]);
        side2 = atoi(numbers[1]);
        side3 = atoi(numbers[2]);
        
        // order side 1 2 3 from min to high
        if (side1 > side2) {
            tmp = side1;
            side1 = side2;
            side2 = tmp;
        }
        if (side2 > side3) {
            tmp = side2;
            side2 = side3;
            side3 = tmp;
        }
        
        ribbonLength += side1 * 2 + side2 * 2 + side1 * side2 * side3;
        
        area1 = side1 * side2;
        area2 = side2 * side3; 
        area3 = side1 * side3;

        surface += (2 * area1 + 2 * area2 + 2 * area3);

        if (area1 <= area2 && area1 <= area3) surface += area1;
        else if (area2 <= area1 && area2 <= area3) surface += area2;
        else if (area3 <= area1 && area3 <= area2) surface += area3;
        else printf("can't find smallest side %d %d %d", area1, area2, area3);
    }

    printf("Day 2015.02.1: %d\n", surface);
    printf("Day 2015.02.2: %d\n", ribbonLength);
    return 0;
}
