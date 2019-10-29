<?php

declare(strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Wilbur\HyperfSoar\Listener;

use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;

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

	public function listen() : array
	{
		return [
			QueryExecuted::class,
		];
	}

	/**
	 * @param object $event
	 */
	public function process(object $event) : void
	{
		if ($event instanceof QueryExecuted && $this->soarIsEnabled) {
			$sql = $event->sql;
			if (!Arr::isAssoc($event->bindings)) {
				foreach ($event->bindings as $key => $value) {
					$sql = Str::replaceFirst('?', "'{$value}'", $sql);
				}
			}
			$eventSqlList   = (array) Context::get(\class_basename(__CLASS__));
			$eventSqlList[] = $sql;
			Context::set(\class_basename(__CLASS__), $eventSqlList);
		}
	}
}
