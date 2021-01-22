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
return [
    'enabled' => env('SOAR_ENABLED', env('APP_ENV') === 'local'),
    '-soar-path' => env('SOAR_PATH', ''), // soar 二进制文件存储路径
    '-test-dsn' => [
        'host' => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
        'port' => env('SOAR_TEST_DSN_PORT', '3306'),
        'dbname' => env('SOAR_TEST_DSN_DBNAME', 'database'),
        'username' => env('SOAR_TEST_DSN_USER', 'root'),
        'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
        'disable' => env('SOAR_TEST_DSN_DISABLE', false),
    ],
    '-report-type' => env('SOAR_REPORT_TYPE', 'json'), // 报告输出格式
    '-sampling' => env('SOAR_SAMPLING', true), // 是否开启数据采样开关
];
