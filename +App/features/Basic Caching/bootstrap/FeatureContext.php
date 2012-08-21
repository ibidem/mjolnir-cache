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
     * @When /^I store a value "([^"]*)" under a key "([^"]*)"$/
     */
    public function iStoreAValueUnderAKey($data, $key)
    {
        $this->driver->set($key, $data);
    }

    /**
     * @Then /^I should get the value "([^"]*)" when I ask for the cache "([^"]*)"$/
     */
    public function iShouldGetTheValueWhenIAskForTheCache($data, $key)
    {
        \app\expects($data)->equals($this->driver->get($key));
    }
	
	/**
     * @Given /^I delete the cache key "([^"]*)"$/
     */
    public function iDeleteTheCacheKey($key)
    {
        $this->driver->delete($key);
    }

    /**
     * @Then /^the cache "([^"]*)" should be null$/
     */
    public function theCacheShouldBeNull($key)
    {
        \app\expects(null)->equals($this->driver->get($key));
    }

} # context
