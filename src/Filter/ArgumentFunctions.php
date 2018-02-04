<?php

namespace QueryBuildHelper\Filter;

class ArgumentFunctions
{

	/**
	 * @param array $eq
	 * @return \Closure
	 */
	public static function BOOL($eq = ['TRUE','true','t','1',1])
	{
		return function ($value) use ($eq) {
			if (is_array($value)) {
				return array_map(function ($val) use ($eq) {
					return (is_array($eq) ? in_array($val, $eq) : $val === $eq) ? 'TRUE' : 'FALSE';
				}, $value);
			} else {
				return (is_array($eq) ? in_array($value, $eq) : $value === $eq) ? 'TRUE' : 'FALSE';
			}
		};
	}

	/**
	 * @param string $format
	 * @return \Closure
	 */
	public static function DATE($format = 'Y-m-d')
	{
		return function ($value) use ($format) {
			if (is_array($value)) {
				return array_map(function ($val) use ($format) {
					if (is_string($val)) {
						$time = strtotime($val);
						return $time ? date($format, $time) : null;
					} elseif (is_numeric($val)) {
						return date($format, $val);
					} else return null;
				}, $value);
			} else {
				if (is_string($value)) {
					$time = strtotime($value);
					return $time ? date($format, $time) : null;
				} elseif (is_numeric($value)) {
					return date($format, $value);
				} else return null;
			}
		};
	}

	/**
	 * @return \Closure
	 */
	public static function DEF()
	{
		return function($v) {
			return $v;
		};
	}
}