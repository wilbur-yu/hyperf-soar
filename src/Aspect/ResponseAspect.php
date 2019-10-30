<?php
declare(strict_types = 1);

namespace Wilbur\HyperfSoar\Aspect;

use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Exception\Exception;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Swoole\Coroutine\Channel;
use Wilbur\HyperfSoar\Listener\QueryExecListener;
use Wilbur\HyperfSoar\SoarService;

/**
 * @Aspect
 */
class ResponseAspect extends AbstractAspect
{
	public $classes = [
		'Hyperf\HttpServer\Response::json',
	];

	/**
	 * @Value("soar")
	 * @var bool
	 */
	protected $soarConfig;

	/**
	 * @Inject
	 * @var SoarService
	 */
	protected $soar;

	/**
	 * @param ProceedingJoinPoint $proceedingJoinPoint
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function process(ProceedingJoinPoint $proceedingJoinPoint)
	{
		$sqlKey = \class_basename(QueryExecListener::class);

		if (!$this->soarConfig['enabled'] || !Context::has($sqlKey)) {
			return $proceedingJoinPoint->process();
		}

		$eventSqlList = Context::get($sqlKey);

		$explains = [];
		$channel  = new Channel();

		foreach ($eventSqlList as $sql) {
			\co(function () use ($sql, $channel) {
				$soar = $this->soar->score($sql);
				if ($this->soarConfig['-report-type'] === 'json') {
					$explain['sql']     = $sql;
					$explain['explain'] = \json_decode($soar, true);
				}
				$channel->push($explain);
			});
			$explains[] = $channel->pop();
		}

		$response = $proceedingJoinPoint->process();

		$oldBody = \json_decode($response->getBody()->getContents(), true);
		$newBody = \json_encode(\array_merge($oldBody, ['soar' => $explains]), \JSON_UNESCAPED_UNICODE);

		return $response->withBody(new SwooleStream($newBody));
	}
}