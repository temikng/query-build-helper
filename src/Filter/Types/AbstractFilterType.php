<?php

namespace QueryBuildHelper\Filter\Types;

abstract class AbstractFilterType implements FilterTypeInterface
{
	/**
	 * @var string
	 */
	protected $key;
	/**
	 * @var bool
	 */
	protected $active = false;
	/**
	 * @var string
	 */
	protected $query;
	/**
	 * @var array
	 */
	protected $args;
	/**
	 * @var array
	 */
	protected $params;

	public function __construct($key, $params)
	{
		$this->key = $key;
		$this->params = $params;
	}

	public abstract function prepareData($typeData, $paramData);

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return array
	 */
	public function getOriginArgs()
	{
		return $this->args;
	}

	/**
	 * @return array
	 */
	public function getArgs()
	{
		return $this->args;
	}

	/**
	 * @param mixed $args
	 * @return $this
	 */
	public function setArgs($args)
	{
		$this->args = $args;
		return $this;
	}
}