# Heap attacks

Stack-based buffer overflows can induce code execution, because in the stack, there is a mixture of data and control bytes. Same goes for the heap, where application data is mixed with heap management data.

## Heap crash course

- locally used data -> stack
- global data, size known at compile time -> .bss or .data
- global dynamic data -> heap
Heap is, in C, managed by `malloc` and `free`
Attributes: rw-

Main libc challenge: have a fast heap (syscalls for updating the heap size are expensive), but generate as little fragmentation as possible.

To manage the heap, one can think about another dynamic structure - some kind of meta-heap - but it's more complex than it seems. The usual choice is to have heap metadata as part of the heap. For each allocation block, we need to know:
- free/used boolean
- top heap block boolean
- block size
- previous block size
- @ free block: address of next free block

Basically two linked lists:
- prevsize + size = all blocks doubly-linked list
- nextfree = free blocks linked-list

nota bene: practically: size is *aligned* (divisible by 4), so in practice, we have two bookkeeping bytes:
```
data    ...
mgmt    next free (only if free)
mgmt    no prev, or prev size
mgmt    size | free | top
```

## Exploits

**Heap overflow**: Let the heap blocks be A|B|C. If A's data is overflown, B's metadata can be controlled so as to make the thid heap block appear as being somewhere else.

**Double free**: an application frees the same resource twice. It can be allocated twice thereafter, leading to concurrent writes.

**Invalid free**: an application gives to `free` a pointer that is not at the beginning of a block. An attacker can possibly forge a fake header there.

**Use after free**: an application uses a resources after it has been freed.

To couteract these attacks, we must have management data separate from application data so as to make the proper checks. But a proper implementation of this has not been found yet.

More [here](https://heap-exploitation.dhavalkapil.com/attacks/)