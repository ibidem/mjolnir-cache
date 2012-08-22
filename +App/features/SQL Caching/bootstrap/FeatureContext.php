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
		// do nothing
    }
	
	/**
	 * @BeforeFeature
	 */
	static function before()
	{
		\app\SQL::database('ibidem_testing');
	}
	
	/**
	 * @AfterFeature
	 */
	static function after()
	{
		\app\SQL::database('default');
	}
	
	/**
	 * @var \app\SQLStash
	 */
	protected $querie, $result;

    /**
     * @Given /^a mock database with ids "([^"]*)" and titles "([^"]*)"$/
     */
    public function aMockDatabaseWithIdsAndTitles($ids, $titles)
    {
		$ids = \explode(', ', $ids);
		$titles = \explode(', ', $titles);
		
		\app\Schematic::destroy
			(
				'test_table'
			);
		
		\app\Schematic::table
			(
				'test_table', 
				'
					`id`    :key_primary,
					`title` :title,
					
					PRIMARY KEY(`id`)
				'
			);
		
		\app\SQL::begin();
		
		$inserter = \app\SQL::prepare
			(
				__METHOD__,
				'
					INSERT INTO test_table
						(id, title) VALUES (:id, :title)
				'
			)
			->bind_int(':id', $id)
			->bind(':title', $title);
		
		foreach ($ids as $idx => $id)
		{
			$title = $titles[$idx];
			$inserter->execute();
		}
		
		\app\SQL::commit();
    }

    /**
     * @Given /^a sql-cache querie$/
     */
    public function aSqlCacheQuerie()
    {
        $this->querie = \app\SQLStash::prepare
			(
				__METHOD__, 
				'SELECT * FROM :table'
			)
			->key('__feature')
			->identity('__test')
			->table('test_table')
			->timers(['my_table_update']);
    }

    /**
     * @When /^I execute the querie$/
     */
    public function iExecuteTheQuerie()
    {
        $this->result = $this->querie->fetch_all();
    }

    /**
     * @Then /^I should get the ids "([^"]*)"$/
     */
    public function iShouldGetTheIds($expected)
    {
		$actual = \app\Collection::implode(', ', $this->result, function ($i, $v) {
			return $v['id'];
		});
		
		\app\expects($expected)->equals($actual);
    }

    /**
     * @Given /^I should get the titles "([^"]*)"$/
     */
    public function iShouldGetTheTitles($expected)
    {
        $actual = \app\Collection::implode(', ', $this->result, function ($i, $v) {
			return $v['title'];
		});
		
		\app\expects($expected)->equals($actual);
    }
	
    /**
     * @When /^I add an item with id "([^"]*)" and title "([^"]*)" to the database$/
     */
    public function iAddAnItemWithIdAndTitleToTheDatabase($id, $title)
    {
		\app\SQL::prepare
			(
				__METHOD__,
				'
					INSERT INTO test_table
						(id, title) VALUES (:id, :title)
				'
			)
			->set_int(':id', $id)
			->set(':title', $title)
			->execute();
		
		\app\Stash::purge(['my_table_update']);
    }

    /**
     * @Given /^I ask for all items again$/
     */
    public function iAskForAllItemsAgain()
    {
        $this->result = $this->querie->fetch_all();
    }

    /**
     * @When /^I limit the querie to page "([^"]*)", limit "([^"]*)" and offset "([^"]*)"$/
     */
    public function iLimitTheQuerieToPageLimitAndOffset($page, $limit, $offset)
    {
		$page = (int) $page;
		$limit = (int) $limit;
		$offset = (int) $offset;
        $this->querie->page($page, $limit, $offset);
    }

    /**
     * @Given /^I sort the query by "([^"]*)"$/
     */
    public function iSortTheQueryBy($sort)
    {
        $criterias = \explode(', ', $sort);
		$order = [];
		foreach ($criterias as $criteria)
		{
			$sort_order = \explode(' => ', $criteria);
			$order[$sort_order[0]] = $sort_order[1];
		}
		
		$this->querie->order($order);
    }

    /**
     * @Given /^I constraint the query to "([^"]*)"$/
     */
    public function iConstraintTheQueryTo($conditions)
    {
        $criterias = \explode(', ', $conditions);
		$constraints = [];
		foreach ($criterias as $criteria)
		{
			$constraint = \explode(' => ', $criteria);
			
			if ($constraint[1] === 'true')
			{
				$constraint[1] = true;
			}
			
			if ($constraint[1] === 'false')
			{
				$constraint[1] = false;
			}
			
			$constraints[$constraint[0]] = $constraint[1];
		}
		
		$this->querie->constraints($constraints);
    }
	
}
