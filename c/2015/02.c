#include <stdio.h>
#include "common.c"

int main() 
{
    int arraySize = 0;
    char** input = readInputAsList("02", &arraySize);
    char line[50];

    int i, side1, side2, side3, area1, area2, area3, tmp, surface = 0;

    for (i = 0; i < arraySize; i++) {
        char** numbers = strSplit2(input[i], 'x');
        
        side1 = (int)numbers[0];
        side2 = (int)numbers[1];
        side3 = (int)numbers[2];
        
        area1 = 2 * side1 * side2;
        area2 = 2 * side2 * side3; 
        area3 = 2 * side1 * side3;

        surface += (area1 + area2 + area3);

        if (area1 < area2 && area1 < area3) surface += area1;
        else if (area2 < area1 && area2 < area3) surface += area2;
        else if (area3 < area1 && area3 < area2) surface += area3;
    }

    printf("Day 2015.02.1: %d\n", surface); // 1588178
    // printf("Day 2015.02.2: %d\n");
    return 0;
}
