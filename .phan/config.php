<?php
return [
    'target_php_version' => '7.3',
    'directory_list' => [
        'src/',
        'vendor/',
    ],
    'exclude_analysis_directory_list' => [
        'vendor/',
        'src/Infrastructure/Migrations/',
    ],
    'plugins' => [
        'AlwaysReturnPlugin',
        'DollarDollarPlugin',
        'DuplicateArrayKeyPlugin',
        'DuplicateExpressionPlugin',
        'InvalidVariableIssetPlugin',
        'InvokePHPNativeSyntaxCheckPlugin',
        'NoAssertPlugin',
        'NonBoolBranchPlugin',
        'NonBoolInLogicalArithPlugin',
        'PHPUnitNotDeadCodePlugin',
        'PregRegexCheckerPlugin',
        'PrintfCheckerPlugin',
        'SleepCheckerPlugin',
        'UnknownElementTypePlugin',
        'UnreachableCodePlugin',
    ],
];
