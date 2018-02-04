<?php

namespace QueryBuildHelper\Filter;

use QueryBuildHelper\ExecutableInterface;
use QueryBuildHelper\Filter\Types\AbstractFactoryTypes;
use QueryBuildHelper\Filter\Types\AbstractFilterType;

abstract class AbstractFilter implements ExecutableInterface
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
	protected $paramPrefix;
	/**
	 * @var AbstractFactoryTypes
	 */
	protected $factoryTypes;
	/**
	 * @var array
	 */
	protected $args;
	/**
	 * @var AbstractFilterType[]
	 */
	protected $filter;

	public function __construct(array $data, array $defaultValues = [], $startQuery = '', $endQuery = '')
	{
		$this->data = $data;
		$this->defaultValues = $defaultValues;
		$this->startQuery = $startQuery;
		$this->endQuery = $endQuery;
		$this->paramPrefix = 'f_';
		$this->filter = [];
		$this->args = [];
	}

	/**
	 * @return string
	 */
	public abstract function getQuery();

	/**
	 * @return array
	 */
	public abstract function getArgs();

	/**
	 * @param $prefix
	 * @return $this
	 */
	public function setParamPrefix($prefix)
	{
		$this->paramPrefix = $prefix;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getParamPrefix()
	{
		return $this->paramPrefix;
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
	public function setStartQuery($query)
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
	public function setEndQuery($query)
	{
		$this->endQuery = $query;
		return $this;
	}

	/**
	 * @return AbstractFactoryTypes
	 */
	public function getFactoryTypes()
	{
		return $this->factoryTypes;
	}

	/**
	 * @param AbstractFactoryTypes $factoryTypes
	 * @return $this
	 */
	public function setFactoryTypes($factoryTypes)
	{
		$this->factoryTypes = $factoryTypes;
		return $this;
	}
}