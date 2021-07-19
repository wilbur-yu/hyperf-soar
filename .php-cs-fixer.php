<?php

$header = <<<'EOF'
This file is part of Hyperf.

@link     https://www.hyperf.io
@document https://hyperf.wiki
@contact  wenber.yu@creative-life.club
@license  https://github.com/hyperf/hyperf/blob/master/LICENSE
EOF;

return (new PhpCsFixer\Config())->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'               => true, // 开启预设的规则
        '@Symfony'            => true, // 开启预设的规则
        '@DoctrineAnnotation' => true, // 开启预设的规则
        '@PhpCsFixer'         => true,
        'header_comment'                             => [
            'comment_type' => 'PHPDoc',
            'header'       => $header,
            'separate'     => 'none',
            'location'     => 'after_declare_strict',
        ],
        'array_syntax' => [
            'syntax' => 'short', // array 使用短声明
        ],
        'list_syntax' => [
            'syntax' => 'short', // list 使用短声明
        ],
        'concat_space' => [
            'spacing' => 'one', // 点拼接必须前后有空格分割
        ],
        'declare_equal_normalize' => [
            'space' => 'single', // 严格类型声明语句中的等号应包含空格
        ],
        'blank_line_before_statement' => [ // 空行换行必须在任何已配置的语句之前
            'statements' => [
                'break',
                'continue',
                'declare',
                'return',
                'throw',
                'try'
            ],
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author'
            ],
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class', 'function', 'const', // 按顺序use导入
            ],
            'sort_algorithm' => 'alpha',
        ],
        'single_line_comment_style' => [
            'comment_types' => [
            ],
        ],
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'constant_case' => [
            'case' => 'lower', // 常量(true,false,null)使用大写(upper)还是小写(lower)语法
        ],
        'binary_operator_spaces' => [
            'default' => 'align_single_space', // 等号对齐、数字箭头符号对齐
        ],
        'increment_style' => true, // 自增自减运算符开启前置样式
        'lowercase_cast' => false, // 类型强制小写
        'array_indentation' => true, // 数组的每个元素必须缩进一次
        'no_superfluous_phpdoc_tags' => false, // 移出没有用的注释
        'normalize_index_brace'                      => true,
        'class_attributes_separation'                => true,
        'combine_consecutive_unsets'                 => true, // 当多个 unset 使用的时候，合并处理
        'declare_strict_types'                       => true,
        'linebreak_after_opening_tag'                => true,
        'lowercase_static_reference'                 => true, // 静态调用为小写
        'no_useless_else'                            => true, // 删除没有使用的else节点
        'no_useless_return'                          => true, // 删除没有使用的return语句
        'no_unused_imports'                          => true, // 删除没用到的use
        'no_singleline_whitespace_before_semicolons' => true, //禁止只有单行空格和分号的写法
        'not_operator_with_successor_space'          => true,
        'not_operator_with_space'                    => false,
        'ordered_class_elements'                     => true,
        'php_unit_strict'                            => false,
        'single_quote'                               => true, // 简单字符串应该使用单引号代替双引号
        'standardize_not_equals'                     => true, // 使用 <> 代替 !=
        'multiline_comment_opening_closing'          => true,
        'self_accessor'                              => true, // 在当前类中使用 self 代替类名
        'no_whitespace_in_blank_line'                => true, // 删除空行中的空格
        'no_empty_statement'                         => true, // 去除多余的分号
        'no_extra_blank_lines'                       => true,
        'no_blank_lines_after_class_opening'         => true,
        'include'                                    => true,
        'no_trailing_comma_in_list_call'             => true,
        'no_leading_namespace_whitespace'            => true,

        // 在方法参数和方法调用中，每个逗号之前不能有空格，每个逗号之后必须有空格。参数列表可以分成多行，后面的每一行都缩进一次。这样做时，列表中的第一项必须在下一行上，并且每行必须只有一个参数。
        'method_argument_space'                      => [
            'on_multiline'                     => 'ensure_fully_multiline', // 确保多行参数列表中的每个参数都在自己的行上
            'after_heredoc'                    => false, // 是否应删除heredoc末尾和逗号之间的空格
            'keep_multiple_spaces_after_comma' => false, // 逗号后是否保留多个空格
        ],

        'phpdoc_separation' => true, // 不同注释部分按照单空行隔开
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_indent' => true, // phpdoc 缩进
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => [
                'param', 'return', 'throws', 'type', 'var', 'property'
            ]
        ],
        'align_multiline_comment' => [
            'comment_type' => 'all_multiline', // 多行注释的每一行都必须带有星号[PSR-5]，并且必须与第一行对齐。
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);