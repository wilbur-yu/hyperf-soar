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

use Guanguans\SoarPHP\Soar;

use Guanguans\SoarPHP\Support\OsHelper;

use function config;

class SoarService extends Soar
{
    use Exec;

    public function __construct(array $config = null)
    {
        $config = $config ?? config('soar');
        $soarPath = $config['-soar-path'];
        $config['-report-type'] = 'json';
        unset($config['enabled'], $config['cut_classes'], $config['-soar-path']);
        parent::__construct($config, $soarPath);
    }

    protected function getDefaultSoarPath(): string
    {
        if (OsHelper::isWindows()) {
            return __DIR__.'/../vendor/guanguans/soar-php/bin/soar.windows-amd64';
        }
        if (OsHelper::isMacOS()) {
            return __DIR__.'/../vendor/guanguans/soar-php/bin/soar.darwin-amd64';
        }

        return __DIR__.'/../vendor/guanguans/soar-php/bin/soar.linux-amd64';
    }
}
