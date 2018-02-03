<?php

namespace QueryBuildHelper;

interface ExecutableInterface
{
	/**
	 * @param array $params
	 */
	public function exec($params);
}