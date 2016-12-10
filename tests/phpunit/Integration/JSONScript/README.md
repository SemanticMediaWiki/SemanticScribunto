## Integration tests

- `ask-001.json` Test `mw.smw.ask` using functions defined in module.smw.lua

### Assertions

Integration tests aim to prove that the "integration" between MediaWiki,
Semantic MediaWiki, and Scribunto works at a sufficient level therefore assertion
may only check or verify a specific part of an output or data to avoid that
system information (DB ID, article url etc.) distort to overall test results.

### Add a new test case

- Follow the `ask-001.json` example on how to structure the JSON file (setup,
  test etc.)
- Add example pages with content (including value annotations `[[SomeProperty::SomeValue]]`)
  expected to be tested
- If necessary add a new lua module file to the Fixtures directory and import the
  content for the test (see `ask-001.json`)
