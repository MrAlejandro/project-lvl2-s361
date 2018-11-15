<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function \Differ\getDiff;

class DifferTest extends TestCase
{
    public function testGenerateDiffForJsonFiles()
    {
        $diff = getDiff(__DIR__ . '/fixtures/json/before.json', __DIR__ . '/fixtures/json/after.json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/json/expected.diff', $diff);
    }

    public function testGenerateDiffForYamlFiles()
    {
        $diff = getDiff(__DIR__ . '/fixtures/yaml/before.yaml', __DIR__ . '/fixtures/yaml/after.yaml');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/yaml/expected.diff', $diff);
    }
}
