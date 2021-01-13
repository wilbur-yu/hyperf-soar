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
namespace Wilbur\HyperfSoar\Aspect;

use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Engine\Channel;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Wilbur\HyperfSoar\Listener\QueryExecListener;
use Wilbur\HyperfSoar\SoarService;
use function array_merge;
use function class_basename;
use function co;
use function json_decode;
use function json_encode;
use const JSON_UNESCAPED_UNICODE;

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
     * @throws \Guanguans\SoarPHP\Exceptions\InvalidArgumentException
     * @throws \Hyperf\Di\Exception\Exception
     * @throws \JsonException
     * @return mixed
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $sqlKey = class_basename(QueryExecListener::class);

        if (! $this->soarConfig['enabled'] || ! Context::has($sqlKey)) {
            return $proceedingJoinPoint->process();
        }

        $eventSqlList = Context::get($sqlKey);

        $explains = [];
        $channel = new Channel();

        foreach ($eventSqlList as $sql) {
            co(function () use ($sql, $channel) {
                $explain = [];
                $soar = $this->soar->score($sql);
                if ($this->soarConfig['-report-type'] === 'json') {
                    $explain['sql'] = $sql;
                    $explain['explain'] = json_decode($soar, true, 512, JSON_THROW_ON_ERROR);
                }
                $channel->push($explain);
            });
            $explains[] = $channel->pop();
        }

        $response = $proceedingJoinPoint->process();

        $oldBody = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $newBody = json_encode(
            array_merge(
                $oldBody,
                ['soar' => $explains]
            ),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
        );

        return $response->withBody(new SwooleStream($newBody));
    }
}
