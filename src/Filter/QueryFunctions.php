<?php

namespace QueryBuildHelper\Filter;

use QueryBuildHelper\Filter\Types\FilterTypeInterface;

class QueryFunctions
{
	/**
	 * @param string $field
	 * @return \Closure
	 */
	public static function BETWEEN_DATE($field)
	{
		/**
		 * @param FilterTypeInterface $type
		 * @return string
		 */
		return function (FilterTypeInterface $type) use ($field) {
			$args = $type->getOriginArgs();
			$query = [];
			if (!is_array($args)) return '';
			if (isset($args[0]) && $args[0] !== '') {
				$query[] = "CAST({$field} AS date) >= CAST(? AS date)";
			}
			if (isset($args[1]) && $args[1] !== '') {
				$query[] = "CAST({$field} AS date) <= CAST(? AS date)";
			}
			return '(' . join(' AND ', $query) . ')';
		};
	}
}