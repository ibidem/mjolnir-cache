@ibidem @caching
Feature: Caching
  In order for caching to work.
  As a developer
  I need to be able to store and retrieve values.

  Scenario Outline: Updating a cache entry.
     Given a cache driver "<driver>"
       And a cached entry "<key>" with "<value_one>"
       And I update "<key>" to "<value_two>"
      Then I should see cache entry "<key>" as "<value_two>"

  Examples:
		| driver    | key | value_one | value_two |
		| apc       | key | old       | new       |
		| file      | key | old       | new       |
		| memcached | key | old       | new       |

  Scenario Outline: Forcing a cache entry to expire.
     Given a cache driver "<driver>"
       And a cached entry "key" with "whatever value" and expires "<expires>"
      Then cache entry "key" should be null

  Examples:
		| driver    | expires |
		| apc       | 0       |
		| file      | 0       |
		| memcached | 0       | 
		| apc       | -1      |
		| file      | -1      |
		| memcached | -1      |

  Scenario Outline: Removing a cache entry.
     Given a cache driver "<driver>"
       And a cached entry "test" with "value1"
      When I delete the cache entry "test"
      Then cache entry "test" should be null

  Examples:
		| driver    | 
		| apc       | 
		| file      |      
		| memcached | 

  Scenario Outline: Removing a cache entry by tag.
     Given a cache driver "<driver>"
       And a cache entry "tagged" with value "something" and tag "test"
      When I purge the tag "test"
      Then cache entry "tagged" should be null

  Examples:
		| driver    | 
		| apc       | 
		| file      |      
		| memcached | 
