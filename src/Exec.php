<?php

declare(strict_types=1);
/**
 * This file is part of project hyperf-soar.
 *
 * @author   wenber.yu@creative-life.club
 * @link     https://github.com/wilbur-yu
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Exceptions\RuntimeException;
use Guanguans\SoarPHP\Support\OS;
use Swoole\Coroutine\System;

trait Exec
{
    /**
     * @param  string  $command
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    public function exec(string $command): string
    {
        OS::isWindows() && $command = 'powershell '.$command;
        $result = System::exec($command);

        if ($result['code'] !== 0) {
            throw new RuntimeException(sprintf("Command error: '%s'", $result['output']));
        }

        return $result['output'];
    }
}
