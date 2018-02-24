// http://adventofcode.com/2015/day/2

#include <stdio.h>
#include "common.c"

int main()
{
    char* input = readInput("03");
    char* strCoords = calloc(10,sizeof(char));
    char c, tmp[5];
    
    int moveCount = strlen(input), i = 0, vc = 1;
    char** visitedCoords = calloc(moveCount, sizeof(char*)); // 10 is max size for each coords
    visitedCoords[0] = "0_0";
    int* coords = calloc(2, sizeof(int));

    while ((c = input[i++]) != '\0') {
        if (c == '<') coords[0] -= 1;
        else if (c == '>') coords[0] += 1;
        else if (c == '^') coords[1] -= 1;
        else if (c == 'v') coords[1] += 1;

        strCoords[0] = '\0';
        strcat(strCoords, itoa(coords[0], tmp, 10));
        strcat(strCoords, "_");
        strcat(strCoords, itoa(coords[1], tmp, 10));

        if (inStrArray(visitedCoords, moveCount, strCoords) == 0) {
            visitedCoords[vc++] = strCoords;
            strCoords = calloc(10, sizeof(char));
        }
    }

    printf("Day 2015.03.1 %d\n", vc);

    // -----------------

    visitedCoords = calloc(moveCount, sizeof(char*)); // 10 is max size for each coords
    visitedCoords[0] = "0_0";
    strCoords = calloc(10, sizeof(char));
    i = 0;
    vc = 1;
    short isSantasTurns = 0;
    int santa[2] = {0,0}, robot[2] = {0,0};
    
    while ((c = input[i++]) != '\0') {
        if (isSantasTurns = !isSantasTurns) {
            coords = santa;
        } else {
            coords = robot;
        }

        if (c == '<') coords[0] -= 1;
        else if (c == '>') coords[0] += 1;
        else if (c == '^') coords[1] -= 1;
        else if (c == 'v') coords[1] += 1;

        strCoords[0] = '\0';
        strcat(strCoords, itoa(coords[0], tmp, 10));
        strcat(strCoords, "_");
        strcat(strCoords, itoa(coords[1], tmp, 10));

        if (inStrArray(visitedCoords, moveCount, strCoords) == 0) {
            visitedCoords[vc++] = strCoords;
            strCoords = calloc(10, sizeof(char));
        }
    }

    printf("Day 2015.03.2 %d\n", vc);

    return 0;
}