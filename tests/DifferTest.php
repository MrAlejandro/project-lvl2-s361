<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function \Differ\getDiff;
use function \Differ\buildDiffFromAST;
use function \Differ\buildAST;

class DifferTest extends TestCase
{
    public function testGenerateDiffForJsonFiles()
    {
        $diff = getDiff(__DIR__ . '/fixtures/json/before.json', __DIR__ . '/fixtures/json/after.json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/json/expected.diff', $diff);

        $diff = getDiff(__DIR__ . '/fixtures/json/nested_before.json', __DIR__ . '/fixtures/json/nested_after.json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/json/nested_expected.diff', $diff);
    }

    public function testGenerateDiffForYamlFiles()
    {
        $diff = getDiff(__DIR__ . '/fixtures/yaml/before.yaml', __DIR__ . '/fixtures/yaml/after.yaml');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/yaml/expected.diff', $diff);
    }

    public function testBuildDiffForNestedAST()
    {
        $ast = include(__DIR__ . '/fixtures/ast/ast.php');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/ast/expected.diff', buildDiffFromAST($ast));
    }

    public function testBuildAST()
    {
        $before = include(__DIR__ . '/fixtures/ast/before.php');
        $after = include(__DIR__ . '/fixtures/ast/after.php');
        $expected = include(__DIR__ . '/fixtures/ast/expected_ast.php');
        $this->assertEquals($expected, buildAST($before, $after));
    }
}
