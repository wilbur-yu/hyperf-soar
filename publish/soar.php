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
return [
    'enabled' => env('SOAR_ENABLED', true),
    'cut_classes' => [
        'Hyperf\HttpServer\Response::json',
    ],
    // soar 二进制文件的绝对路径
    '-soar-path' => env('SOAR_PATH'),
    // 测试环境配置
    '-test-dsn' => [
        'host' => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
        'port' => env('SOAR_TEST_DSN_PORT', '3306'),
        'dbname' => env('SOAR_TEST_DSN_DBNAME', 'database'),
        'username' => env('SOAR_TEST_DSN_USER', 'root'),
        'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
        'disable' => env('SOAR_TEST_DSN_DISABLE', true),
    ],
    // 是否开启数据采样开关
    '-sampling' => env('SOAR_SAMPLING', true),
    // 允许输出删除重复索引的建议
    '-allow-drop-index' => env('SOAR_ALLOW_DROP_INDEX', true),
    // 是否清理测试环境产生的临时库表
    '-drop-test-temporary' => env('SOAR_DROP_TEST_TEMPORARY', true),
    // 日志输出文件
    '-log-output' => BASE_PATH.'/runtime/logs/soar.log',
    /*
     * 启发式算法相关配置
     */
    '-max-join-table-count' => 5,
    '-max-group-by-cols-count' => 5,
    '-max-distinct-count' => 5,
    '-max-index-cols-count' => 5,
    '-max-total-rows' => 9999999,
    '-spaghetti-query-length' => 2048,
    // '-allow-drop-index' => false,
];
