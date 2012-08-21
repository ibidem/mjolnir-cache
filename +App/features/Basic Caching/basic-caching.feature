@ibidem @caching
Feature: Basic Caching
  In order for caching to work.
  As a developer
  I need to be able to store and retrieve values.

  Scenario Outline: Basic storing and retrieving.
     Given a cache driver "<driver>"
      When I store a value "<value>" under a key "<key>"
      Then I should get the value "<value>" when I ask for the cache "<key>"

  Examples:
		| driver    | value | key                                     |
		| file      | test  | a_key                                   |
		| file      | 1     | 1                                       |
		| file      | qwert | sjkfasklf jklafjaslkfjaslkfjaslf fklasl |
		| memcached | test  | a_key                                   |
