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
	 * @var \ibidem\types\Stash
	 */
	protected $driver;
	
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
		// do nothing
    }
	
	/**
     * @Given /^a cache driver "([^"]*)"$/
     */
    public function aCacheDriver($driver)
    {
		$class = '\app\Stash_'.\ucfirst($driver);
        $this->driver = $class::instance();
    }

	/**
     * @Given /^a cached entry "([^"]*)" with "([^"]*)"$/
     */
    public function aCachedEntryWithValue($key, $value)
    {
		$this->driver->set($key, $value);
    }

    /**
     * @Given /^I update "([^"]*)" to "([^"]*)"$/
     */
    public function iUpdateToValue($key, $value)
    {
        $this->driver->set($key, $value);
    }

    /**
     * @Then /^I should see cache entry "([^"]*)" as "([^"]*)"$/
     */
    public function iShouldSeeCacheEntryAsValue($key, $new_value)
    {
		\app\expects($new_value)->equals($this->driver->get($key));
    }
	
    /**
     * @Given /^a cached entry "([^"]*)" with "([^"]*)" and expires "([^"]*)"$/
     */
    public function aCachedEntryWithAndExpires($key, $value, $expires)
    {
		$this->driver->set($key, $value, $expires);
    }

    /**
     * @Then /^cache entry "([^"]*)" should be null$/
     */
    public function cacheEntryShouldBeNull($key)
    {
		\app\expects(null)->equals($this->driver->get($key));
    }
	
	/**
     * @Given /^I delete the cache entry "([^"]*)"$/
     */
    public function iDeleteTheCacheEntry($key)
    {
		$this->driver->delete($key);
    }
	
	/**
     * @Given /^a cache entry "([^"]*)" with value "([^"]*)" and tag "([^"]*)"$/
     */
    public function aCacheEntryWithValueAndTag($key, $value, $tag)
    {
		$this->driver->store($key, $value, [$tag]);
    }

    /**
     * @When /^I purge the tag "([^"]*)"$/
     */
    public function iPurgeTheTag($tag)
    {
        $this->driver->purge([$tag]);
    }
	
    /**
     * @Given /^a cache entry "([^"]*)" with value "([^"]*)" and a tag "([^"]*)"$/
     */
    public function aCacheEntryWithValueAndATag($key, $value, $tag)
    {
        $this->driver->store($key, $value, [$tag]);
    }

}
