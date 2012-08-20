Feature: 
  In order for caching to work.
  A cache entry should be able to a store values.
  A cache entry should expire.
  A cache entry should be removable.
  A cache entry should have a default.

  Scenario: Updating a cache entry.
     Given a cached entry "test" with "value1"
       And I update "test" to "value2"
      Then I should see cache entry "test" as "value2"

  Scenario: Forcing a cache entry to expire.
     Given a cached entry "test" with "some value" and expires "0"
      Then cache entry "test" should be null

  Scenario: Expiring a cache entry via a negative value.
     Given a cached entry "test" with "some value" and expires "-1"
      Then cache entry "test" should be null

  Scenario: Removing a cache entry.
     Given a cached entry "test" with "value1"
       And I delete the cache entry "test"
      Then cache entry "test" should be null