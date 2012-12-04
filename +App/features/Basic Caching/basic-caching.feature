@mjolnir @caching @drivers
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
	| memcached | a b c | a_key       |
	| memcached | 1     | 1           |
	| memcached | 0     | 666         |
	| memcached | xyz   | abc cde fgh |
	| apc       | a b c | a_key       |
	| apc       | 1     | 1           |
	| apc       | 0     | 666         |
	| apc       | xyz   | abc cde fgh |

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
	| memcached | a b c | a_key       |
	| memcached | 0     | 666         |
	| memcached | xyz   | abc cde fgh |
	| apc       | a b c | a_key       |
	| apc       | 0     | 666         |
	| apc       | xyz   | abc cde fgh |

  Scenario Outline: Updating a previously set entry.
     Given a cache driver "<driver>"
      When I store a value "<value_one>" under a key "<key>"
	   And I store a value "<value_two>" under a key "<key>"
      Then I should get the value "<value_two>" when I ask for the cache "<key>"

  Scenarios:
	| driver    | value_one | value_two | key   |
	| file      | abc       | xyz       | a_key |
	| memcache  | abc       | xyz       | a_key |
	| memcached | abc       | xyz       | a_key |
	| apc       | abc       | xyz       | a_key |

  Scenario Outline: Storing and retrieving arrays
	Given a cache driver "<driver>"
	When I store the array "<array>" under a key "some_key"
	Then I should get the same array back when I ask for the cache "some_key"

  Scenarios:
	| driver    | array                  |
	| file      | 0 => 1, 1 => 3, 2 => 2 |
	| file      | 0 => a, 1 => b, 2 => c |
	| file      | 0 => d                 |
	| memcache  | 0 => 1, 1 => 3, 2 => 2 |
	| memcache  | 0 => a, 1 => b, 2 => c |
	| memcache  | 0 => d                 |
	| memcached | 0 => 1, 1 => 3, 2 => 2 |
	| memcached | 0 => a, 1 => b, 2 => c |
	| memcached | 0 => d                 |
	| apc       | 0 => 1, 1 => 3, 2 => 2 |
	| apc       | 0 => a, 1 => b, 2 => c |
	| apc       | 0 => d                 |
