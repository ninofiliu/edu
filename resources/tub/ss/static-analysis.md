# Static analysis

<!-- ? -->

*We retrict our attention to the use of static analysis to detect bugs in our own programs - not to analyse malware.*

Compilers normally don't do a security checkup when compiling due to an alarm fatigue/false negative/speed trade-off, so specialized tools are being used, like Clang, Coverity, and Fortify.

A program is a control flow, and a data flow. Both need to be statically analyzed. The main tool used in control flow analysis is a CFG (control flow graph). Data flow analysis asks the question *what property of this variable holds at this location*?

Increasing scope:
- intra-procedural: single function. fast. done by compilers.
- inter-procedural: a few functions. slow. done by static analyzers.
- global: whole program, during or after linking. very slow. done only by advanced static analyzers.

Data flow analysis ussually rely on **tainting**: if a variable is user-defined, everything that it "touches" is potentially attacker-controlled. But how to propagate taint?

Increasing precision:
- flow-sensitive: sensitive to the order of instructions (taint propagates "downward only").
- context-sensitive: sensitive to the calling context of the procedure.
- path-sensitive: if/else/switches/... are analyzed differently.