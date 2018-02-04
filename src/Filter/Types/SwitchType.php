<?php

namespace QueryBuildHelper\Filter\Types;

class SwitchType extends AbstractFilterType
{
	protected $switch;

	/**
	 * @var AbstractInnerFilterType|null
	 */
	protected $case;
	protected $function;
	protected $args;

	public function prepareData($typeData, $paramData)
	{
		if (is_array($typeData)) {
			if ($typeData['case']) $this->switch = $typeData['case'];
			if (!$this->switch) throw new \Exception("required 'case' parameter in filter data by key: {$this->key}");
			if (!is_array($this->switch)) throw new \Exception("parameter 'case' must be an associate array in filter data by key: {$this->key}");
		} else {
			throw new \Exception("filter: {$this->key} must be an array");
		}

		if (array_key_exists($paramData, $this->switch)) {
			$case = $this->switch[$paramData];

			$type = null;
			if (isset($case['t'])) $type = $case['t'];
			if (isset($case['type'])) $type = $case['type'];
			if (!isset($type)) $type = 'simple';

			$filterType = FactoryTypes::getInnerTypeByName($type, $paramData, $this->params);
			$filterType->prepareData($case);

			$this->case = $filterType;
		}
	}

	public function exec()
	{
		if ($this->case) {
			$this->case->exec();

			$this->active = $this->case->isActive();
			if ($this->active) {
				$this->query = $this->case->getQuery();
				$this->args = $this->case->getArgs();
			}
		}
	}
}