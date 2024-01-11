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

use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Collection\Arr;
use Hyperf\Stringable\Str;


#[Listener]
class QueryExecListener implements ListenerInterface
{
    protected bool $soarIsEnabled;
    public const SQL_RECORD_KEY = 'wilbur-yu-hyperf-soar-sql-listener';

    public function __construct(ConfigInterface $config)
    {
        $this->soarIsEnabled = $config->get('soar.enabled', false);
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
                foreach ($event->bindings as $value) {
                    $sql = Str::replaceFirst('?', "'$value'", $sql);
                }
            }
            $eventSqlList = (array)Context::get(self::SQL_RECORD_KEY);
            $eventSqlList[] = $sql;
            Context::set(self::SQL_RECORD_KEY, $eventSqlList);
        }
    }
}
