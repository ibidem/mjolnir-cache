<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

\mjolnir\cfs\Mjolnir::behat();

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
	/**
	 * @var \mjolnir\types\Stash
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

    /**
     * @When /^I store the array "([^"]*)" under a key "([^"]*)"$/
     */
    public function iStoreTheArrayUnderAKey($the_array, $key)
    {
		$elements = \explode(', ', $the_array);
		$array = [];
		foreach ($elements as $element)
		{
			$def = \explode(' => ', $element);

			if ($def[1] === 'true')
			{
				$def[1] = true;
			}

			if ($def[1] === 'false')
			{
				$def[1] = false;
			}

			if (\is_numeric($def[0]))
			{
				$def[0] = (int) $def[0];
			}

			if (\is_numeric($def[1]))
			{
				$def[1] = (int) $def[1];
			}

			$array[$def[0]] = $def[1];
		}

		$this->last_array = $array;

		$this->driver->set($key, $array);
    }

    /**
     * @Then /^I should get the same array back when I ask for the cache "([^"]*)"$/
     */
    public function iShouldGetTheSameArrayBackWhenIAskForTheCache($key)
    {
        $actual = $this->driver->get($key);
		\app\expects($this->last_array)->equals($actual);
    }

} # context
