# TODO

- Add tests:
  - Add a "memory" Symfony tests environment.
  - Set up an "acceptance" test suite only using the "memory" environment.
  - Set up an "integration" test suite that runs tests in both the "test" and "memory" environments. Use only the Symfony KernelTestCase.
  - Set up an "end-to-end" test suite that runs tests in the "test" environment. Use Symfony WebTestCase.
- Setup CircleCI.
