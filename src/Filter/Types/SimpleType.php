<?php

namespace QueryBuildHelper\Filter\Types;

use QueryBuildHelper\Filter\ArgumentFunctions;

class SimpleType extends AbstractFilterType
{
	protected $function;
	protected $functionActive;
	protected $args;

	public function prepareData($typeData, $paramData)
	{
		if (is_array($typeData)) {
			if (array_key_exists('q', $typeData)) $this->query = $typeData['q'];
			if (array_key_exists('query', $typeData)) $this->query = $typeData['query'];
			if (!$this->query) throw new \Exception("required 'query' parameter in filter data by key: {$this->key}");

			if (array_key_exists('f', $typeData)) $this->function = $typeData['f'];
			if (array_key_exists('func', $typeData)) $this->function = $typeData['func'];
			if (array_key_exists('function', $typeData)) $this->function = $typeData['function'];
			if (!$this->function) $this->function = ArgumentFunctions::DEF();

			if (array_key_exists('fa', $typeData)) $this->functionActive = $typeData['fa'];
			if (array_key_exists('function_active', $typeData)) $this->functionActive = $typeData['function_active'];
		} else {
			$this->query = $typeData;
			$this->function = ArgumentFunctions::DEF();
		}
		$this->args = $paramData;
	}

	public function exec()
	{
		if (!is_callable($this->function)) throw new \Exception("parameter 'function' must be a callable");
		$this->args = call_user_func($this->function, $this->args);

		if (is_callable($this->query)) {
			$this->query = call_user_func($this->query, $this);
		}

		if ($this->functionActive && is_callable($this->functionActive)) {
			$this->active = call_user_func($this->functionActive, $this->args);
		} else {
			if (is_array($this->args)) {
				$isActive = false;
				foreach ($this->args as $aK => $aV) {
					$isActive = isset($this->args) && $this->args !== '';
					if ($isActive) break;
				}
				$this->active = $isActive;
			} else {
				$this->active = isset($this->args) && $this->args !== '';
			}
		}
	}
}