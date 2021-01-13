<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  wenber.yu@creative-life.club
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Wilbur\HyperfSoar;

use Guanguans\SoarPHP\Soar;
use Hyperf\Utils\Arr;
use function config;

class SoarService extends Soar
{
    use Exec;

    public function __construct(array $config = null)
    {
        $config = $config ?? config('soar');

        Arr::forget($config, 'enabled');

        parent::__construct($config);
    }
}
