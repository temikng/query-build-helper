<?php

use PHPUnit\Framework\TestCase;
use QueryBuildHelper\Sort\AbstractSort;
use QueryBuildHelper\Sort\SimpleSort;

class SimpleSortTest extends TestCase
{
	/**
	 * @var AbstractSort
	 */
	public $simpleSort;

	public function setUp()
	{
		$this->simpleSort = new SimpleSort([
				'id'=>'a.id',
				'fio'=>['a.lastname','a.firstname','a.patronymic'],
				'role'=>'b.title',
				'created'=>'a.created',
				'updated'=>'a.updated'
		], ['-created']);
	}

	public function testDefault()
	{
		$expectedOrderByQuery = 'a.created DESC';

		$_REQUEST = [];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());

		$_REQUEST = ['sort'=>''];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());

		$_REQUEST = ['sort'=>'-another'];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());

		$_REQUEST = ['sort'=>['-another1','another2']];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());
	}

	public function testFIO()
	{
		$_REQUEST = ['sort'=>'-fio'];
		$this->simpleSort->exec($_REQUEST);
		$expectedOrderByQuery = 'a.lastname DESC, a.firstname DESC, a.patronymic DESC';
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());

		$_REQUEST = ['sort'=>'fio'];
		$this->simpleSort->exec($_REQUEST);
		$expectedOrderByQuery = 'a.lastname ASC, a.firstname ASC, a.patronymic ASC';
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());
	}

	public function testMultiple()
	{
		$expectedOrderByQuery = 'b.title ASC, a.lastname DESC, a.firstname DESC, a.patronymic DESC';

		$_REQUEST = ['sort'=>['role','-fio']];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());

		$_REQUEST = ['sort'=>['role','another','-fio']];
		$this->simpleSort->exec($_REQUEST);
		$this->assertEquals($expectedOrderByQuery, $this->simpleSort->getQuery());
	}
}