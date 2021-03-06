<?php

use Behat\Behat\Context\ClosuredContextInterface,
	Behat\Behat\Context\TranslatedContextInterface,
	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
	Behat\Gherkin\Node\TableNode;
use app\Assert;

\mjolnir\cfs\Mjolnir::behat();

// @todo LOW - convert database code to mockup when I have time

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
	function __construct(array $parameters)
	{
		$base = \app\CFS::config('mjolnir/base');
		if ( ! isset($base['caching']) || ! $base['caching'])
		{
			throw new \app\Exception('Caching is not enabled.');
		}
	}

	/**
	 * @BeforeFeature
	 */
	static function before()
	{
		\app\SQL::database('mjolnir_testing');

		\app\Schematic::destroy
			(
				'test_table'
			);

		\app\Schematic::table
			(
				'test_table',
				'
					`id`	:key_primary,
					`title` :title,

					PRIMARY KEY(`id`)
				'
			);
	}

	/**
	 * @AfterFeature
	 */
	static function after()
	{
		\app\Schematic::destroy
			(
				'test_table'
			);

		\app\SQL::database('default');
	}

	/**
	 * @var \app\SQLStash
	 */
	protected $querie, $result;

	/**
	 * @Given /^a mock database with ids "([^"]*)" and titles "([^"]*)"$/
	 */
	function aMockDatabaseWithIdsAndTitles($ids, $titles)
	{
		$ids = \explode(', ', $ids);
		$titles = \explode(', ', $titles);

		\app\SQL::prepare
			(
				__METHOD__.':truncate',
				'
					TRUNCATE TABLE test_table
				'
			)
			->run();

		\app\SQL::begin();

		$inserter = \app\SQL::prepare
			(
				__METHOD__,
				'
					INSERT INTO test_table
						(id, title) VALUES (:id, :title)
				'
			)
			->bindnum(':id', $id)
			->bindstr(':title', $title);

		foreach ($ids as $idx => $id)
		{
			$title = $titles[$idx];
			$inserter->run();
		}

		\app\SQL::commit();
	}

	/**
	 * @Given /^a sql-cache querie$/
	 */
	function aSqlCacheQuerie()
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
	 * @When /^I execute the querie( again)?$/
	 */
	function iExecuteTheQuerie()
	{
		$this->result = $this->querie->fetch_all();
	}

	/**
	 * @Then /^I should get the ids "([^"]*)"$/
	 */
	function iShouldGetTheIds($expected)
	{
		$actual = \app\Arr::implode(', ', $this->result, function ($i, $v) {
			return $v['id'];
		});

		Assert::that($expected)->equals($actual);
	}

	/**
	 * @Given /^I should get the titles "([^"]*)"$/
	 */
	function iShouldGetTheTitles($expected)
	{
		$actual = \app\Arr::implode(', ', $this->result, function ($i, $v) {
			return $v['title'];
		});

		Assert::that($expected)->equals($actual);
	}

	/**
	 * @When /^I add an item with id "([^"]*)" and title "([^"]*)" to the database$/
	 */
	function iAddAnItemWithIdAndTitleToTheDatabase($id, $title)
	{
		\app\SQL::prepare
			(
				__METHOD__,
				'
					INSERT INTO test_table
						(id, title) VALUES (:id, :title)
				'
			)
			->num(':id', $id)
			->str(':title', $title)
			->run();

		\app\Stash::purge(['my_table_update']);
	}

	/**
	 * @Given /^I ask for all items again$/
	 */
	function iAskForAllItemsAgain()
	{
		$this->result = $this->querie->fetch_all();
	}

	/**
	 * @When /^I limit the querie to page "([^"]*)", limit "([^"]*)" and offset "([^"]*)"$/
	 */
	function iLimitTheQuerieToPageLimitAndOffset($page, $limit, $offset)
	{
		$page = (int) $page;
		$limit = (int) $limit;
		$offset = (int) $offset;
		$this->querie->page($page, $limit, $offset);
	}

	/**
	 * @Given /^I sort the query by "([^"]*)"$/
	 */
	function iSortTheQueryBy($sort)
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
	function iConstraintTheQueryTo($conditions)
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
