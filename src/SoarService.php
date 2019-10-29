<?php
declare(strict_types = 1);

namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Exceptions\InvalidArgumentException;
use Guanguans\SoarPHP\Soar;
use Hyperf\Utils\Arr;

class SoarService extends Soar
{
	public function __construct(array $config = null)
	{
		$config = $config ?? \config('soar');

		// Arr::forget($config, 'enabled');

		parent::__construct($config);
	}

	/**
	 * @param $sql
	 *
	 * @return string|null
	 * @throws InvalidArgumentException
	 */
	public function jsonExplain($sql) : ?string
	{
		return $this->exec("echo '$sql;' | $this->soarPath " . '-report-type=json');
	}

	public function score($sql) : ?string
	{
		\var_dump("echo '$sql;' | $this->soarPath " . $this->getFormatConfig($this->config));

		return $this->exec("echo '$sql;' | $this->soarPath " . $this->getFormatConfig($this->config));
	}
}