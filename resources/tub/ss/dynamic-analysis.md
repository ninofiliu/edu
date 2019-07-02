# Dynamic analysis: fuzzing

Goal: find input that leads to a crash, a hang, or an undefined behavior.

**black-box testing**: random inputs are fed. Finding a bug is very unlikely.

**white-box testing**: programs needs to be instrumented so that the fuzzer understands its internals and evaluate variables like code coverage. This can drastically improve the search speed compared to black box testing.
- compile time instrumentation: low perf overhead, doesn't work well on dynamic languages
- runtime instrumentation: high perf overhead because checking must be inserted at runtime, but dynamic languages are handles better