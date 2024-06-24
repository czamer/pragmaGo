<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude([
        'var',
        'vendor',
    ])
    ->exclude([
        'config',
    ]);

return (new Config())
    ->setFinder($finder)
    ->setCacheFile('var/cache/php-cs-fixer.cache')
    ->setRules([
        '@PhpCsFixer' => true,
        '@PER-CS2.0' => true,
        '@PHP83Migration' => true,
        'concat_space' => ['spacing' => 'one'],
        'date_time_immutable' => true,
        'declare_strict_types' => true,
        'final_class' => true,
        'global_namespace_import' => true,
        'multiline_whitespace_before_semicolons' => true,
        'spaces_inside_parentheses' => false,
        'void_return' => true,
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'none',
                'method' => 'one',
                'property' => 'none',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
            ],
        ],
    ]);
