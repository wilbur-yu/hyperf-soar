<?php
declare(strict_types = 1);

namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Exceptions\InvalidArgumentException;
use Guanguans\SoarPHP\Exceptions\RuntimeException;
use Swoole\Coroutine\System;

trait Exec
{
	/**
	 * @param $command
	 *
	 * @return string|null
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 */
	public function exec($command) : ?string
	{
		if (!is_string($command)) {
			throw new InvalidArgumentException('Command type must be a string');
		}

		if (false === stripos($command, 'soar')) {
			throw new InvalidArgumentException(sprintf("Command error: '%s'", $command));
		}

		$result = System::exec($command);

		if (0 !== $result['code']) {
			throw new RuntimeException(sprintf("Command error: '%s'", $result['output']));
		}

		return $result['output'];
	}
}