@ibidem @caching
Feature: Basic Caching
  In order for caching to work.
  As a developer
  I need to be able to store and retrieve values.

  Scenario Outline: Basic storing.
     Given a cache driver "<driver>"
      When I store a value "<value>" under a key "<key>"
      Then the key "<key>" should return that "<value>" when called

  Examples:
		| driver    | value | key
		| file      | test  | a_key
		| memcached | test  | a_key
