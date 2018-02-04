<?php

namespace QueryBuildHelper\Filter;

use QueryBuildHelper\Filter\Types\FactoryTypes;
use Exception;

class SimpleFilter extends AbstractFilter
{
	public function __construct(array $data, array $defaultValues = [], $startQuery = '', $endQuery = '')
	{
		parent::__construct($data, $defaultValues, $startQuery, $endQuery);
		$this->factoryTypes = new FactoryTypes();
	}

	/**
	 * @param array $params
	 * @throws Exception
	 */
	public function exec($params)
	{
		$this->filter = [];

		if (!is_array($this->defaultValues)) throw new Exception();
		foreach ($this->defaultValues as $paramKey => $paramData) {
			if (is_callable($paramData)) {
				$params[$paramKey] = $paramData(array_key_exists($paramKey, $params) ? $params[$paramKey] : null);
			} else {
				if (array_key_exists($paramKey, $params)) {
					$params[$paramKey] = (!isset($params[$paramKey]) || $params[$paramKey] === '') ? $paramData : $params[$paramKey];
				} else {
					$params[$paramKey] = $paramData;
				}
			}
		}

		$data = $this->data;
		$filters = [];

		// fill filters by income values
		foreach ($params as $paramKey => $paramData) {
			if ($this->paramPrefix) {
				if (strpos($paramKey, $this->paramPrefix) !== 0) continue;
				$filterKey = substr($paramKey, strlen($this->paramPrefix));
			} else {
				$filterKey = $paramKey;
			}

			if (!array_key_exists($filterKey, $data)) continue;

			$filterData = $data[$filterKey];
			if (is_array($filterData)) {

				$type = null;
				if (isset($filterData['t'])) $type = $filterData['t'];
				if (isset($filterData['type'])) $type = $filterData['type'];
				if (!isset($type)) $type = 'simple';

				$filterType = $this->getFactoryTypes()->getTypeByName($type, $filterKey, $params);
				$filterType->prepareData($filterData, $paramData);

				$filters[$filterKey] = $filterType;
			} elseif (is_string($filterData) || is_callable($filterData)) {
				$filterType = $this->getFactoryTypes()->getTypeByName('simple', $filterKey, $params);
				$filterType->prepareData($filterData, $paramData);

				$filters[$filterKey] = $filterType;
			} else {
				throw new Exception("undefined filter data by key: {$filterKey}");
			}
		}

		foreach ($filters as $filterKey => &$filterData) {
			$filterData->exec();
		}

		$this->filter = $filters;
	}

	public function getQuery($glue = ' AND ')
	{
		$queries = [];

		if (is_string($glue)) {
			foreach ($this->filter as $filter) {
				if ($filter->isActive()) {
					$queries[] = $filter->getQuery();
				}
			}
		} elseif (is_array($glue)) {
			foreach ($glue as $keyGlue => $valueGlue) {
				if (array_key_exists($keyGlue, $this->filter)) {
					$filter = $this->filter[$keyGlue];
					if ($filter->isActive()) {
						$queries[] = $filter->getQuery();
						$queries[] = $valueGlue;
					}
				}
			}
			$glue = ' ';
		} else {
			throw new Exception('Wrong glue type');
		}

		if (!count($queries)) {
			$queries[] = '1=1';
		}

		return ($this->startQuery ? $this->startQuery . ' ': '') . join($glue, $queries) . ($this->endQuery ? ' ' . $this->endQuery: '');
	}
	public function getArgs($glue = null)
	{
		$args = [];

		if (!isset($glue)) {
			foreach ($this->filter as $filter) {
				if ($filter->isActive()) {
					$a = $filter->getArgs();
					if (is_array($a)) {
						$args = array_merge($args, $a);
					} else {
						$args[] = $a;
					}
				}
			}
		} else {
			//TODO: merge args by glue
		}

		return $args;
	}
}