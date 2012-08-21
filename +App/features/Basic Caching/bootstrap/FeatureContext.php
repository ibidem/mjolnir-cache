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
	
	

} # context
