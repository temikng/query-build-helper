<?php

namespace QueryBuildHelper\Filter\Types;

abstract class AbstractFactoryTypes
{
	/**
	 * @param string $name
	 * @param string $key
	 * @param mixed $params
	 * @return AbstractFilterType
	 */
	public abstract function getTypeByName($name, $key, $params);

	/**
	 * @param $name
	 * @param $key
	 * @param $params
	 * @return AbstractFilterType
	 */
	public abstract function getInnerTypeByName($name, $key, $params);
}