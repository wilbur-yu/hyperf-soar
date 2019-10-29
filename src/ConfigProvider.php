<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Wilbur\HyperfSoar;

use Wilbur\HyperfSoar\Aspect\ResponseAspect;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
        	'aspects'=>[
        		ResponseAspect::class
			],
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
			'publish' => [
				[
					'id' => 'config',
					'description' => 'The config for soar.',
					'source' => __DIR__ . '/../publish/soar.php',
					'destination' => BASE_PATH . '/config/autoload/soar.php',
				],
			],
        ];
    }
}
