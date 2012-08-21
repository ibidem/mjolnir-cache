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
	 * @var \ibidem\types\TaggedStash
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
     * @Given /^I store a value "([^"]*)" under a key "([^"]*)" and tag "([^"]*)"$/
     */
    public function iStoreAValueUnderAKeyAndTag($data, $key, $tag)
    {
        $this->driver->store($key, $data, [$tag]);
    }

    /**
     * @When /^I purge the tag "([^"]*)"$/
     */
    public function iPurgeTheTag($tag)
    {
        $this->driver->purge([$tag]);
    }

    /**
     * @Then /^the cache "([^"]*)" should be null$/
     */
    public function theCacheShouldBeNull($key)
    {
        \app\expects(null)->equals($this->driver->get($key));
    }

} # context
