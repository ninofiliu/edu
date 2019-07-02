# Address space layout randomization

Main families of SBBOs rely either on:
- placing code on stack and jumping to it
- returning and dereferencing specific offset in shared libraries or text section

ASLR randomizes of the base addresses of:
- the stack
- the heap
- the shared libs

Given sufficient entropy it becomes very hard for an attacker to use addresses as part of their attack. Yet, the text section is usually not ASLRed and that can be exploited in [return-oriented programming](./rop.md).

By the way, in order to call libc functions with the base address of libc randomized, a "jump-to-libc" table is used. When calling a libc function for the first time, a dynamic linker will resolve the dynamic address.