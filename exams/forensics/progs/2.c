// buffer overflow

#include <stdio.h>
#include <string.h>

int main(int argc, char** argv) {
	char test[10];
	char input[10];

	if (argc!=2) {
		printf("usage: prog2 <argument>\n");
		return 1;
	} else {
		strcpy(test,"AAAAAAAAA");
		strcpy(input,argv[1]);
		printf("input : %s\n",input);
		printf("test : %s\n",test);
		if (strcmp(test,"overflow")==0) {
			printf("WIN\n");
			return 0;
		} else {
			printf("FAIL\n");
			return 2;
		}
	}
}
