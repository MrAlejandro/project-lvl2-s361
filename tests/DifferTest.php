<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

use function \Differ\getDiff;

class DifferTest extends TestCase
{
    /** @var \org\bovigo\vfs\vfsStreamDirectory */
    protected $fs;

    public function setUp()
    {
        $jsonFiles = [
            'json' => [
                'file1.json' => $this->getFirstJsonFileContent(),
                'file2.json' => $this->getSecondJsonFileContent(),
            ]
        ];

        $this->fs = vfsStream::setup('root', 444, $jsonFiles);
    }

    public function testReadFiles()
    {
        $diff = getDiff($this->fs->url() . '/json/file1.json', $this->fs->url() . '/json/file2.json');
        $expected = implode(
            PHP_EOL,
            [
                '{',
                '    host: hexlet.io',
                '  + timeout: 20',
                '  - timeout: 50',
                '  - proxy: 123.234.53.22',
                '  + verbose: true',
                '}'
            ]
        );
        $this->assertEquals($expected, $diff);
    }

    protected function getFirstJsonFileContent(): string
    {
        return <<<JSON
{
  "host": "hexlet.io",
  "timeout": 50,
  "proxy": "123.234.53.22"
}        
JSON;
    }

    protected function getSecondJsonFileContent(): string
    {
        return <<<JSON
{
  "timeout": 20,
  "verbose": true,
  "host": "hexlet.io"
}
JSON;
    }
}