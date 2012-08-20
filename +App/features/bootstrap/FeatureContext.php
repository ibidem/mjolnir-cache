<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

\ibidem\base\Mjolnir::behat();

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        \app\Collection::implode(' ', ['hello', 'world'], function ($i, $k) { return $k; });
    }

	/**
     * @Given /^a cached entry "([^"]*)" with "([^"]*)"$/
     */
    public function aCachedEntryWithValue($key, $value)
    {
        \app\Stash::set($key, $value);
    }

    /**
     * @Given /^I update "([^"]*)" to "([^"]*)"$/
     */
    public function iUpdateToValue($key, $value)
    {
        \app\Stash::set($key, $value);
    }

    /**
     * @Then /^I should see cache entry "([^"]*)" as "([^"]*)"$/
     */
    public function iShouldSeeCacheEntryAsValue($key, $expected_value)
    {
        $got = \app\Stash::get($key);
		if ($got !== $expected_value)
		{
			throw new \app\Exception('Expected ['.$expected_value.'], but got ['.$got.'].');
		}
    }
	
    /**
     * @Given /^a cached entry "([^"]*)" with "([^"]*)" and expires "([^"]*)"$/
     */
    public function aCachedEntryWithAndExpires($key, $value, $expires)
    {
        \app\Stash::set($key, $value, $expires);
    }

    /**
     * @Then /^cache entry "([^"]*)" should be null$/
     */
    public function cacheEntryShouldBeNull($key)
    {
        $got = \app\Stash::get($key);
		if ($got !== null)
		{
			throw new \app\Exception('Expected [null], but got ['.$got.'].');
		}
    }
	
	/**
     * @Given /^I delete the cache entry "([^"]*)"$/
     */
    public function iDeleteTheCacheEntry($key)
    {
        \app\Stash::delete($key);
    }
	
	
}
