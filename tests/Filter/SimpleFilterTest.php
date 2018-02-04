<?php

use PHPUnit\Framework\TestCase;

use QueryBuildHelper\Filter\AbstractFilter;
use QueryBuildHelper\Filter\SimpleFilter;
use QueryBuildHelper\Filter\ArgumentFunctions as QBHFAF;
use QueryBuildHelper\Filter\QueryFunctions as QBHFQF;

class SimpleFilterTest extends TestCase
{
	/**
	 * @var AbstractFilter
	 */
	public $simpleFilter;

	public function setUp()
	{
		$this->simpleFilter = new SimpleFilter([
				'id'  =>"CAST(id AS text) LIKE CONCAT(?,'%')",
				'name'=>['q'=>"UPPER(b.name) LIKE CONCAT('%',UPPER(?),'%')"],
				'del' =>['q'=>'b.is_del = ?','f'=>QBHFAF::BOOL()],
				'date'=>[
						't'   =>'switch',
						'case'=>[
								'created'=>['p'=>'created','q'=>'CAST(created AS date) >= CAST(? AS date)','f'=>QBHFAF::DATE()],
								'between'=>['p'=>['df','dt'],'q'=>QBHFQF::BETWEEN_DATE('created'),'f'=>QBHFAF::DATE()]
						]
				]
		], [
				'f_del'=>'0',
				'f_date'=>'between',
				'df'=>'2017-01-01',
				'dt'=>'2018-01-01'
		]);
	}

	public function testDefault()
	{
		$expectedWhereQuery = 'b.is_del = ? AND (CAST(created AS date) >= CAST(? AS date) AND CAST(created AS date) <= CAST(? AS date))';
		$expectedWhereArgs = ['FALSE','2017-01-01','2018-01-01'];

		$_REQUEST = [];
		$this->simpleFilter->exec($_REQUEST);
		$this->assertEquals($expectedWhereQuery, $this->simpleFilter->getQuery());
		$this->assertEquals($expectedWhereArgs, $this->simpleFilter->getArgs());

		$_REQUEST = ['f_another'=>'test'];
		$this->simpleFilter->exec($_REQUEST);
		$this->assertEquals($expectedWhereQuery, $this->simpleFilter->getQuery());
		$this->assertEquals($expectedWhereArgs, $this->simpleFilter->getArgs());
	}

	public function test()
	{
		$expectedWhereQuery = "UPPER(b.name) LIKE CONCAT('%',UPPER(?),'%') AND b.is_del = ? AND (CAST(created AS date) >= CAST(? AS date) AND CAST(created AS date) <= CAST(? AS date))";
		$expectedWhereArgs = ['test','FALSE','2017-01-01','2018-01-01'];

		$_REQUEST = ['f_name'=>'test','f_del'=>'0'];
		$this->simpleFilter->exec($_REQUEST);
		$this->assertEquals($expectedWhereQuery, $this->simpleFilter->getQuery());
		$this->assertEquals($expectedWhereArgs, $this->simpleFilter->getArgs());

		$_REQUEST = ['f_name'=>'test'];
		$this->simpleFilter->exec($_REQUEST);
		$this->assertEquals($expectedWhereQuery, $this->simpleFilter->getQuery());
		$this->assertEquals($expectedWhereArgs, $this->simpleFilter->getArgs());
	}
}
