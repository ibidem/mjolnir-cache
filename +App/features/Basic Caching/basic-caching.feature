@ibidem @caching @drivers
Feature: Basic Caching
  In order for caching to work.
  As a developer
  I need to be able to store and retrieve values.

  Scenario Outline: Basic storing and retrieving.
     Given a cache driver "<driver>"
      When I store a value "<value>" under a key "<key>"
      Then I should get the value "<value>" when I ask for the cache "<key>"
    
  Scenarios:
	| driver    | value | key         |
	| file      | a b c | a_key       |
	| file      | 1     | 1           |
	| file      | 0     | 666         |
	| file      | xyz   | abc cde fgh |
	| memcache  | a b c | a_key       |
	| memcache  | 1     | 1           |
	| memcache  | 0     | 666         |
	| memcache  | xyz   | abc cde fgh |

  Scenario Outline: Basic deleting.
     Given a cache driver "<driver>"
      When I store a value "<value>" under a key "<key>"
       And I delete the cache key "<key>"
      Then the cache "<key>" should be null

  Scenarios:
	| driver    | value | key         |
	| file      | a b c | a_key       |
	| file      | 0     | 666         |
	| file      | xyz   | abc cde fgh |
	| memcache  | a b c | a_key       |
	| memcache  | 0     | 666         |
	| memcache  | xyz   | abc cde fgh |

  Scenario Outline: Updating a previously set entry.
     Given a cache driver "<driver>"
      When I store a value "<value_one>" under a key "<key>"
	   And I store a value "<value_two>" under a key "<key>"
      Then I should get the value "<value_two>" when I ask for the cache "<key>"

  Scenarios:
	| driver    | value_one | value_two | key   |
	| file      | abc       | xyz       | a_key |
	| memcache  | abc       | xyz       | a_key |
