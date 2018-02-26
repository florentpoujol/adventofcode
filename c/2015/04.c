// https://adventofcode.com/2015/day/4

// only works in the debugger, otherwise the second call to md5() makes the program crash

#include <stdio.h>
#include <string.h>
#include "md5.c"

int main()
{
    char* md5String;
    char* largeInput = malloc(20 * sizeof(char));
    char* temp = malloc(20 * sizeof(char));

    int i = 0, part1 = 0, part2 = 0;
    while (part1 == 0 || part2 == 0) {
        largeInput[0] = '\0';
        strcat(largeInput, "bgvyzdsv");
        strcat(largeInput, itoa(i++, temp, 10));

        md5String = md5(largeInput);
        // printf("%s %s \n", largeInput, md5String);
        if (part1 == 0 && strstr(md5String, "00000") == md5String) {
            part1 = i;
        }
        if (part2 == 0 && strstr(md5String, "000000") == md5String) {
            part2 = i;
        }
    }

    printf("Day 2015.4.1 %d \n", part1);
    printf("Day 2015.4.2 %d \n", part2);

    return 0;
}
