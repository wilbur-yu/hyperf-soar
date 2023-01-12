<?php

declare(strict_types=1);
/**
 * 本文件属于KK馆版权所有，泄漏必究。
 * This file belong to KKGUAN, all rights reserved.
 */
namespace Wilbur\HyperfSoar\Listener;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;

#[Listener]
class QueryExecListener implements ListenerInterface
{
    public const SQL_RECORD_KEY = 'wilbur-yu-hyperf-soar-sql-listener';

    protected bool $soarIsEnabled;

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
            if (! Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }
            $eventSqlList = (array) Context::get(self::SQL_RECORD_KEY);
            $eventSqlList[] = $sql;
            Context::set(self::SQL_RECORD_KEY, $eventSqlList);
        }
    }
}
