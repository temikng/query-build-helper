<?php

namespace QueryBuildHelper\Filter\Types;

class FactoryTypes extends AbstractFactoryTypes
{
	public function getTypeByName($name, $key, $params)
	{
		switch ($name) {
			case 'simple':
				return new SimpleType($key, $params);
			case 'switch':
				return new SwitchType($key, $params);
			default:
				throw new \Exception("undefined filter type name: {$name}");
		}
	}

	public function getInnerTypeByName($name, $key, $params)
	{
		switch ($name) {
			case 'simple':
				return new SimpleInnerType($key, $params);
			default:
				throw new \Exception("undefined filter inner type name: {$name}");
		}
	}
}