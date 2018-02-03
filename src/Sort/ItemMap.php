<?php

namespace QueryBuildHelper\Sort;

class ItemMap extends AbstractItem
{
	/**
	 * @var AbstractItem[]
	 */
	protected $map;

	/**
	 * ItemMap constructor.
	 * @param string $key
	 * @param AbstractItem[] $map
	 */
	public function __construct($key, $map)
	{
		parent::__construct($key);
		$this->map = $map;
	}

	/**
	 * @return AbstractItem[]
	 */
	public function getMap()
	{
		return $this->map;
	}

	/**
	 * @param AbstractItem[] $map
	 */
	public function setMap($map)
	{
		$this->map = $map;
	}

	/**
	 * @param AbstractItem $item
	 * @return $this
	 */
	public function add($item)
	{
		$this->map[$item->getKey()] = $item;
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function del($key)
	{
		if (array_key_exists($key, $this->map))
			unset($this->map[$key]);
		return $this;
	}
}