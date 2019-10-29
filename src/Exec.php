<?php
declare(strict_types = 1);

namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Exceptions\InvalidArgumentException;
use Swoole\Coroutine\System;

trait Exec
{
	/**
	 * @param $command
	 *
	 * @return string|null
	 * @throws InvalidArgumentException
	 */
	public function exec($command) : ?string
	{
		if (!is_string($command)) {
			throw new InvalidArgumentException('Command type must be a string');
		}

		if (false === stripos($command, 'soar')) {
			throw new InvalidArgumentException(sprintf("Command error: '%s'", $command));
		}

		return System::exec($command);
	}
}