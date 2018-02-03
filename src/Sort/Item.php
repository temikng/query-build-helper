<?php

namespace QueryBuildHelper\Sort;

class Item extends AbstractItem
{
	/**
	 * @var string
	 */
	protected $alias;
	/**
	 * @var string
	 */
	protected $direction;

	/**
	 * Item constructor.
	 * @param string $key
	 * @param string $alias
	 * @param string $direction
	 */
	public function __construct($key, $alias, $direction)
	{
		parent::__construct($key);
		$this->alias = $alias;
		$this->direction = $direction;
	}

	/**
	 * @return string
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @param $alias
	 * @return $this
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDirection()
	{
		return $this->direction;
	}

	/**
	 * @param $direction
	 * @return $this
	 */
	public function setDirection($direction)
	{
		$this->direction = $direction;
		return $this;
	}
}