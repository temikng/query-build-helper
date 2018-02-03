<?php

namespace QueryBuildHelper\Sort;

use QueryBuildHelper\ExecutableInterface;

abstract class AbstractSort implements ExecutableInterface
{
	/**
	 * @var array
	 */
	protected $data;
	/**
	 * @var array
	 */
	protected $defaultValues;
	/**
	 * @var string
	 */
	protected $startQuery;
	/**
	 * @var string
	 */
	protected $endQuery;
	/**
	 * @var string
	 */
	protected $paramKey = 'sort';

	/**
	 * @var AbstractItem[]
	 */
	protected $sort;

	public function __construct(array $data, array $defaultValues, $startQuery = '', $endQuery = '')
	{
		$this->data = $data;
		$this->defaultValues = $defaultValues;
		$this->startQuery = $startQuery;
		$this->endQuery = $endQuery;
		$this->sort = [];
	}

	/**
	 * @return string
	 */
	public abstract function getQuery();

	/**
	 * @return string
	 */
	public function getParamKey()
	{
		return $this->paramKey;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function setParamKey($key)
	{
		$this->paramKey = $key;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getStartQuery()
	{
		return $this->startQuery;
	}

	/**
	 * @param string $query
	 * @return $this
	 */
	public function setStartQuery($query = '')
	{
		$this->startQuery = $query;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEndQuery()
	{
		return $this->endQuery;
	}

	/**
	 * @param string $query
	 * @return $this
	 */
	public function setEndQuery($query = '')
	{
		$this->endQuery = $query;
		return $this;
	}

	/**
	 * @param AbstractItem[] $data
	 * @return $this
	 */
	public function setSort($data)
	{
		$this->sort = $data;
		return $this;
	}

	/**
	 * @return AbstractItem[]
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @param AbstractItem $data
	 * @return $this
	 */
	public function addSort($data)
	{
		$this->sort[$data->getKey()] = $data;
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function delSort($key)
	{
		if (array_key_exists($key, $this->sort))
			unset($this->sort[$key]);
		return $this;
	}
}