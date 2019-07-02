#include <stdlib.h>
#include <stdio.h>
#include <string.h>

int main() {
    char* secret = malloc(10);
    strcpy(secret, "ssshhh");
    printf("%p -> %s\n", secret, secret);
    free(secret);
    printf("%p -> %s\n", secret, secret);
    return 0;
}