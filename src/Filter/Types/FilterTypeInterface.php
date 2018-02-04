<?php

namespace QueryBuildHelper\Filter\Types;

interface FilterTypeInterface
{
	/**
	 * @return bool
	 */
	public function isActive();

	/**
	 * @return string
	 */
	public function getQuery();

	/**
	 * @return array
	 */
	public function getOriginArgs();
	/**
	 * @return array
	 */
	public function getArgs();

	public function exec();
}