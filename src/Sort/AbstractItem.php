<?php

namespace QueryBuildHelper\Sort;

abstract class AbstractItem
{
	/**
	 * @var string
	 */
	protected $key;

	/**
	 * AbstractItem constructor.
	 * @param string $key
	 */
	public function __construct($key)
	{
		$this->key = $key;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @param $key
	 * @return $this
	 */
	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}
}