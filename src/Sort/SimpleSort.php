<?php

namespace QueryBuildHelper\Sort;

use Exception;

class SimpleSort extends AbstractSort
{
	/**
	 * @param string $value
	 * @return null|AbstractItem
	 */
	protected function getSortByValue($value)
	{
		$direction = Directions::ASC;

		if (strlen($value) && $value[0] === '-') {
			$direction = Directions::DESC;
			$value = substr($value, 1);
		}

		$key = null;
		$alias = null;
		foreach ($this->data as $kD => $vD) {
			if (is_numeric($kD)) {
				if ($vD === $value) {
					$alias = $vD;
					$key = $value;
				}
			} else {
				if ($kD === $value) {
					$alias = $vD;
					$key = $value;
				}
			}
		}
		if (!$alias) return null;

		if (is_array($alias)) {
			$result = new ItemMap($key, array_map(function ($a) use ($key, $direction) {
				return new Item($key, $a, $direction);
			}, $alias));
			return $result;
		} else {
			return new Item($key, $alias, $direction);
		}
	}

	/**
	 * @param array $params
	 * @throws Exception
	 */
	public function exec($params)
	{
		$this->sort = [];

		if (array_key_exists($this->paramKey, $params)) {
			$values = $params[$this->paramKey];

			if (!is_array($values)) {
				$values = [ $values ];
			}

			$lengthValues = count($values);
			for ($i = 0; $i < $lengthValues; $i++) {
				$value = $values[$i];
				$result = $this->getSortByValue($value);
				if ($result) {
					$this->sort[$result->getKey()] = $result;
				}
			}
		}

		if (!count($this->sort)) {
			$lengthDefaultValues = count($this->defaultValues);
			for ($i = 0; $i < $lengthDefaultValues; $i++) {
				$value = $this->defaultValues[$i];
				$result = $this->getSortByValue($value);
				if ($result) {
					$this->sort[$result->getKey()] = $result;
				} else throw new Exception('error sort default value: ' . $value);
			}
		}
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->startQuery . $this->buildQueryMap($this->sort) . $this->endQuery;
	}

	/**
	 * @param AbstractItem[] $map
	 * @return string
	 */
	protected function buildQueryMap($map)
	{
		return join(', ', array_map(function ($sort) {
			if ($sort instanceof ItemMap) {
				return $this->buildQueryMap($sort->getMap());
			} elseif ($sort instanceof Item) {
				return $sort->getAlias() . ' ' . $sort->getDirection();
			} else throw new Exception('unknown class');
		}, $map));
	}
}