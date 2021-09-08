<?php

$header = <<<'EOF'
This file is part of project hyperf-soar.

@author   wenbo@wenber.club
@link     https://github.com/wilbur-yu

@link     https://www.hyperf.io
@document https://hyperf.wiki
@contact  wenber.yu@creative-life.club
@license  https://github.com/hyperf/hyperf/blob/master/LICENSE
EOF;

return (new PhpCsFixer\Config())->setRiskyAllowed(true)
    ->setRules(['@PSR12' => true])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )->setUsingCache(false);