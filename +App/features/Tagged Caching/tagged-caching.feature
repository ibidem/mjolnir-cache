@mjolnir @caching @drivers
Feature: Basic Caching
  In order for tagged caching to work.
  As a developer
  I need to be able to store and tag and purge values based on tags.

  Scenario Outline: Basic tagging and purging.
     Given a cache driver "<driver>"
       And I store a value "test" under a key "key_one" and tag "<tag>"
       And I store a value "test" under a key "key_two" and tag "<tag>"
      When I purge the tag "<tag>"
      Then the cache "key_one" should be null
	   And the cache "key_two" should be null

  Scenarios:
	| driver    | tag |
	| file      | xyz |
	| file      | 0   |
	| memcache  | xyz |
	| memcache  | 0   |
	| memcached | xyz |
	| memcached | 0   |
	| APC       | xyz |
	| APC       | 0   |
