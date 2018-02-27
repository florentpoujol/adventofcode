// https://adventofcode.com/2015/day/5

// only works in the debugger, otherwise the second call to md5() makes the program crash

#include <stdio.h>
#include <string.h>
#include "common.c"

int isNice1(char* str)
{
    char c;
    int i = 0, vowelCount = 0, hasTwoConsecutiveLetters = 0;
    
    while ((c = str[i++]) != '\0') {
        if (c == 'a' || c == 'e' || c == 'i' || c == 'o' || c == 'u') {
            vowelCount++;
        }
        if (c == str[i]) {
            hasTwoConsecutiveLetters = 1;
        }    
    }

    return (
        strstr(str, "ab") == NULL &&
        strstr(str, "cd") == NULL &&
        strstr(str, "pq") == NULL &&
        strstr(str, "xy") == NULL &&
        vowelCount >= 3 &&
        hasTwoConsecutiveLetters
    );
}

int isNice2(char* str)
{
    char c, letter2;
    char pair1[3] = "  ", pair2[3] = "  ";
    int i = 0, j = 0, constraint1 = 0, constraint2 = 0, len = strlen(str);
    
    while (i + 2 < len && (c = str[i++]) != '\0') {

        if (constraint1 == 0 && i + 2 < len) {
            pair1[0] = c;
            pair1[1] = str[i];
            j = i + 1;
            while (j + 1 < len) {
                pair2[0] = str[j];
                pair2[1] = str[j + 1];
                j++;

                if (strcmp(pair1, pair2) == 0) {
                    constraint1 = 1;
                    break;
                }
            }   
        }

        if (constraint2 == 0 && i + 1 < len && c == str[i + 1]) {
            constraint2 = 1;
        }
    }

    return constraint1 && constraint2;
}

int main()
{
    int lineCount = 0, nice1Count = 0, nice2Count = 0;
    char** lines = readInputAsList("05", &lineCount);
    char* line;

    while (--lineCount >= 0) {
        line = lines[lineCount];
        
        if (isNice1(line)) {
            nice1Count++;
            // printf("Nice: %s \n", line);
        } 
        // else {
        //     printf("NOT Nice: %s \n", line);
        // }

        if (isNice2(line)) {
            nice2Count++;
        }
    }

    printf("2015.05.1 %d\n", nice1Count);
    printf("2015.05.2 %d\n", nice2Count);

    return 0;
}
