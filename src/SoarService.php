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

use function Hyperf\Config\config;

class SoarService extends Soar
{

    public function __construct(array $config = null)
    {
        $config = $config ?? config('soar');
        $soarPath = $config['-soar-path'];
        $config['-report-type'] = 'json';
        unset($config['enabled'], $config['cut_classes'], $config['-soar-path']);
        parent::__construct($config, $soarPath);
    }
}
