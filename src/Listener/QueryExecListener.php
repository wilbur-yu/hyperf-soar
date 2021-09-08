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

namespace Wilbur\HyperfSoar\Listener;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;

use function class_basename;

/**
 * @Listener
 */
class QueryExecListener implements ListenerInterface
{
    /**
     * @var bool
     */
    protected $soarIsEnabled;

    public function __construct(ConfigInterface $config)
    {
        $this->soarIsEnabled = $config->get('soar.enabled');
    }

    public function listen(): array
    {
        return [
            QueryExecuted::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof QueryExecuted && $this->soarIsEnabled) {
            $sql = str_replace('`', '', $event->sql);
            if (!Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }
            $eventSqlList = (array)Context::get(class_basename(__CLASS__));
            $eventSqlList[] = $sql;
            Context::set(class_basename(__CLASS__), $eventSqlList);
        }
    }
}
