<?php

use Cego\CegoFixer;

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests']);

return CegoFixer::applyRules($finder);
