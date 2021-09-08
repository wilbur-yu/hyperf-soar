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

namespace Wilbur\HyperfSoar\Aspect;

use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Engine\Channel;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;
use Throwable;
use Wilbur\HyperfSoar\Listener\QueryExecListener;
use Wilbur\HyperfSoar\SoarService;
use Psr\Container\ContainerInterface;

use function array_merge;
use function class_basename;
use function co;
use function explode;
use function json_decode;
use function json_encode;

use const JSON_UNESCAPED_UNICODE;

/**
 * @Aspect
 */
class ResponseAspect extends AbstractAspect
{
    /**
     * @Value("soar.cut_classes")
     * @var array
     */
    public $classes;
    /**
     * @Value("soar")
     * @var array
     */
    protected $config;
    /**
     * @Inject()
     * @var \Wilbur\HyperfSoar\SoarService
     */
    protected $service;

    // public function __construct(ContainerInterface $container)
    // {
    //     $this->service = $container->get(SoarService::class);
    //     $this->config = $container->get(ConfigInterface::class)->get('soar');
    //     $this->classes = $this->config['cut_classes'] ?? ['Hyperf\HttpServer\Response::json',];
    // }

    /**
     * @throws \Hyperf\Di\Exception\Exception
     * @throws \JsonException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $sqlKey = class_basename(QueryExecListener::class);

        if (!$this->config['enabled'] || !Context::has($sqlKey) || !is_file($this->config['-soar-path'])) {
            return $proceedingJoinPoint->process();
        }

        $eventSqlList = Context::get($sqlKey);

        $explains = [];
        $channel = new Channel();
        $response = $proceedingJoinPoint->process();
        $oldBody = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        try {
            foreach ($eventSqlList as $sql) {
                co(function () use ($sql, $channel) {
                    $soar = $this->service->score($sql);
                    $explain = [
                        'query' => $sql,
                        'explain' => $this->formatting($soar),
                    ];
                    $channel->push($explain);
                });
                $explains[] = $channel->pop();
            }
        } catch (Throwable $throwable) {
            $explains = [
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ];
        }

        $newBody = json_encode(
            array_merge($oldBody, ['soar' => $explains]),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
        );

        return $response->withBody(new SwooleStream($newBody));
    }

    protected function getScore(?string $severity = null): ?int
    {
        if (!$severity) {
            return null;
        }
        $fullScore = 100;
        $unitScore = 5;
        $levels = explode(',', $severity);
        $subScore = 0;
        foreach ($levels as $level) {
            $level = (int)Str::after($level, 'L');
            $subScore += ($level * $unitScore);
        }

        return $fullScore - $subScore;
    }

    /**
     * @throws \JsonException
     */
    protected function formatting(string $json): array
    {
        $results = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $results = Arr::flatten($results, 1);

        $items = [];
        foreach ($results as $result) {
            $score = $this->getScore($result['Severity']);
            if ($score) {
                $result['Score'] = $score;
            }
            $items[] = $result;
        }

        unset($results);

        return $items;
    }
}
