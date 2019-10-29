<?php
declare(strict_types = 1);

namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Soar;
use Hyperf\Utils\Arr;

class SoarService extends Soar
{
	use Exec;

	public function __construct(array $config = null)
	{
		$config = $config ?? \config('soar');

		Arr::forget($config, 'enabled');

		parent::__construct($config);
	}
}