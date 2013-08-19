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
	| driver     | value | key         |
	| File       | a b c | a_key       |
	| File       | 1     | 1           |
	| File       | 0     | 666         |
	| File       | xyz   | abc cde fgh |
	| Memcache   | a b c | a_key       |
	| Memcache   | 1     | 1           |
	| Memcache   | 0     | 666         |
	| Memcache   | xyz   | abc cde fgh |
	| Memcached  | a b c | a_key       |
	| Memcached  | 1     | 1           |
	| Memcached  | 0     | 666         |
	| Memcached  | xyz   | abc cde fgh |
	| APC        | 1     | 1           |
	| APC        | 0     | 666         |
	| APC        | xyz   | abc cde fgh |
	| TempMemory | 1     | 1           |
	| TempMemory | 0     | 666         |
	| TempMemory | xyz   | abc cde fgh |

  Scenario Outline: Basic deleting.
     Given a cache driver "<driver>"
      When I store a value "<value>" under a key "<key>"
       And I delete the cache key "<key>"
      Then the cache "<key>" should be null

  Scenarios:
	| driver     | value | key         |
	| File       | a b c | a_key       |
	| File       | 0     | 666         |
	| File       | xyz   | abc cde fgh |
	| Memcache   | a b c | a_key       |
	| Memcache   | 0     | 666         |
	| Memcache   | xyz   | abc cde fgh |
	| Memcached  | a b c | a_key       |
	| Memcached  | 0     | 666         |
	| Memcached  | xyz   | abc cde fgh |
	| APC        | a b c | a_key       |
	| APC        | 0     | 666         |
	| APC        | xyz   | abc cde fgh |
	| TempMemory | a b c | a_key       |
	| TempMemory | 0     | 666         |
	| TempMemory | xyz   | abc cde fgh |

  Scenario Outline: Updating a previously set entry.
     Given a cache driver "<driver>"
      When I store a value "<value_one>" under a key "<key>"
	   And I store a value "<value_two>" under a key "<key>"
      Then I should get the value "<value_two>" when I ask for the cache "<key>"

  Scenarios:
	| driver     | value_one | value_two | key   |
	| File       | abc       | xyz       | a_key |
	| Memcache   | abc       | xyz       | a_key |
	| Memcached  | abc       | xyz       | a_key |
	| APC        | abc       | xyz       | a_key |
	| TempMemory | abc       | xyz       | a_key |

  Scenario Outline: Storing and retrieving arrays
	Given a cache driver "<driver>"
	When I store the array "<array>" under a key "some_key"
	Then I should get the same array back when I ask for the cache "some_key"

  Scenarios:
	| driver     | array                  |
	| File       | 0 => 1, 1 => 3, 2 => 2 |
	| File       | 0 => a, 1 => b, 2 => c |
	| File       | 0 => d                 |
	| Memcache   | 0 => 1, 1 => 3, 2 => 2 |
	| Memcache   | 0 => a, 1 => b, 2 => c |
	| Memcache   | 0 => d                 |
	| Memcached  | 0 => 1, 1 => 3, 2 => 2 |
	| Memcached  | 0 => a, 1 => b, 2 => c |
	| Memcached  | 0 => d                 |
	| APC        | 0 => 1, 1 => 3, 2 => 2 |
	| APC        | 0 => a, 1 => b, 2 => c |
	| APC        | 0 => d                 |
	| TempMemory | 0 => 1, 1 => 3, 2 => 2 |
	| TempMemory | 0 => a, 1 => b, 2 => c |
	| TempMemory | 0 => d                 |
