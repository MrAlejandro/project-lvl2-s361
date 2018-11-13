<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

use function \Differ\getDiff;

class DifferTest extends TestCase
{
    public function testGenerateDiffForJsonFiles()
    {
        $diff = getDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json');
        $this->assertStringEqualsFile(__DIR__ . '/fixtures/expected.diff', $diff);
    }
}
