# Side channel attacks

A side-channel attack is any attack based on information gained from the implementation of a computer system (rather than weaknesses in the implemented algorithm itself), like power trace, timing, or cache. Let's focus on cache-based side channel attacks.

Memory pyramid: between the registers and the RAM, there are some caches. They can be inside the CPU core, or shared between all CPU cores.

Caches are basically `f(addr)=content of addr`. In a set-associative cache with n sets and m ways (=lines per set), an address is split into `tag:set:offset`. The set bytes defines the set to use. Memory pointed by this address is placed in any line of the set, the tag bytes is there to check if the data cached is the desired data, the offset byte is the offset address in the cache set line.

**Prime and probe attack**:
1. Priming: occupy all cache with attacker data
2. Let the victim execute code - this will evict some of the attacker's data
3. Probing: measure time of access to attacker data. Attacker can infer which sets have been used.
Based on temporal and spatial locality of programs, ie when arrays store each item in a different set, further information can be inferred. A countermeasure, when using secret-dependent paths in a program, is to always execute all paths so that side channels behave similarly for all secrets.

**Rowhammer**:
DRAM vulnerability in which bit flips can be triggered without access to memory. When doing alternative writes in neighbor rows, DRAM's physical properties leads to bit flips.

**Meltdown**:
The *fetch -> decode -> execute* model can be enhanced by parallelism: fetch i3, decode i2, execute i1 at the same time. This is not ideal because a read exception can be triggered by fetch so this exception will be thrown before instruction execution. What we could do is execute code speculatively and revert state if an exception occurs: untill the retire phase, CPU works with copies of registers. Meltdown relies on the fact that the state is reverted... but not the cache!
1. allocate memory that falls into different cache sets
2. access forbidden memory speculatively
3. access our own array: index = forbidden memory content
4. by probing the cache, we can determine the secret value!
Performances: 503KB/s

**Spectre**:
Branch prediction is used to chose the next instruction to fetch when the execute part has not yet stated which branching will be chosen. This is done by the help of a branch target buffer. If we do `if (i<arr.length)` many times for `i` valid, we can be sure that the CPU will predict a valid `i` even for big values. We can trick the CPU to speculatively read arbitrary data, and get back this value using the same technique as in Meltdown.
This is especially phroblematic when working with sandboxed code, and web browsers seem to be a great playground for attackers: JS code is compiled into machine code, but its security relies on the memory bounds we put into this compiled code, so Spectre can possibly bypass these!

Other side-channel attacks:
- power consumption monitoring on an RSA implementation that uses square-and-multiply exponentiation
- cache-based side channel to monitor tapping and swiping
- flush + reload
- "lightbulb" flush+reload on HTML parsing, combined with levenstein distance to identify visited page. 100 pages, 10 samples -> >90% success
