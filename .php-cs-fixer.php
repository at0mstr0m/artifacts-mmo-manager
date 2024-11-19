<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'single_quote' => true,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'single_space',
                '==' => 'single_space',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_align' => false,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_separation' => true,
        'phpdoc_to_comment' => [
            'ignored_tags' => ['todo', 'var'],
        ],
        'space_after_semicolon' => true,
        'trim_array_spaces' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
        ],
        'declare_strict_types' => true,
        'fully_qualified_strict_types' => [
            'leading_backslash_in_global_namespace' => true,
        ],
        'phpdoc_order' => [
            'order' => [
                'property',
                'property-read',
                'property-write',
                'method',
                'param',
                'return',
                'throws',
                'uses',
                'mixin',
            ],
        ],
        'string_implicit_backslashes' => [
            'single_quoted' => 'ignore',
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'continue',
                'declare',
                'exit',
                'goto',
                'include',
                'include_once',
                'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'yield',
                'yield_from',
            ],
        ],
        'multiline_whitespace_before_semicolons' => true,
        'function_declaration' => true,
        'not_operator_with_successor_space' => true,
    ])
    ->setLineEnding("\n");
