<?php

declare(strict_types = 1);

return [
	'enabled'      => env('SOAR_ENABLED', env('APP_ENV') === 'local'),
	'-soar-path'   => env('SOAR_PATH', ''),
	'-test-dsn'    => [
		'host'     => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
		'port'     => env('SOAR_TEST_DSN_PORT', '3306'),
		'dbname'   => env('SOAR_TEST_DSN_DBNAME', 'database'),
		'username' => env('SOAR_TEST_DSN_USER', 'root'),
		'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
		'disable'  => env('SOAR_TEST_DSN_DISABLE', false),
	],
	'-report-type' => env('SOAR_REPORT_TYPE', 'markdown'),
];