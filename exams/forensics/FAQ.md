# Calling conventions

Scheme for how subroutines (callees) receive parameters from their caller and how they return a result.

* CDECL
  * Arguments are passed on the stack, right to left
  * Integer values and memory addresses are returned in EAX
  * Floating point values are returned in ST0x87
  * caller cleans the stack
* STDCALL
  * same as CDEL, but callee cleans the stack
* FASTCALL
  * same as CDEL, but the 2 or 3 first params are passed in registers
* Microsoft
  * first 4 params in registers
  * remaining params passed on the stack, right to left
  * return value in EAX
* SystemV AMD64 ABI (Linux, Max OSX, Solaris)
  * first 6 params in registers
  * remaining params passed on the stack
  * return value in RAX


# Entropy

pdf, jpg, compressed files, enc files: 7.8+
code: 5
machine code: 2



# Windows Shellbags

Shellbags are files stored in registries that maintain the size/view/icon/position of folders when using explorer. They are useful for forensics investigation because information persist in them even after the directory has been deleted. Infos that can be found:
* Where the user has traversed
* Last write time
* For folder and zip files
* Persist even for removable drives
* Persist even for securely deleted files



# PLT/GOT

Global offset table: converts position-independent address calculations to absolute locations.
Procedure linkage table: converts position-independent function calls to absolute locations.
The linker can't resolve execution transfer between two dynamic objects (ex: program that calls `libc`'s `puts`), instead, calls are placed in the program's PLT, and the runtime linker takes it from there (that's why its puts@plt and not puts@libc that appears in the disassembly).



# Prefetch

In Windows. Performance functionality. Page faults are monitored, then data is stored in `.pf` files; contains:
* DLLs used by the executable
* number of times the app has been launched
* timestamp of last exec
* creation date of the pf file



# EXT3

EXT3 is a journaled filesystem that is commonly used in the Linux kernel. By default, metadata is journaled, and file content is not, eventhough that's an option. Creation time is not stored by ext3.

A journaling file system is a file system that keeps track of changes not yet committed to the file system's main part by recording the intentions of such changes. In the event of a crash, such filesystems can be brought back more quickly.



# Intercepting traffic

SPAN ports are used to actively duplicate packets sent to another port:
* HW errors are dropped
* traffic exceed capacity -> drop
* both traffic directions are copied onto the same port

TAPS are passive devices that duplicate electronically the whole traffic.
* Traffic is exactly the same
* No delay, do not alter content
* Normally unidirectional