<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Exceptions\DiffFilesExtensionMismatchException;
use Exceptions\InvalidFormattedFileException;
use Exceptions\UnknownFileExtensionException;
use Exceptions\CannotReadFileException;

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

    public function testIncompatibleFilesExtension()
    {
        $this->expectException(DiffFilesExtensionMismatchException::class);
        getDiff(__DIR__ . '/fixtures/yaml/before.yaml', __DIR__ . '/fixtures/json/after.json');
    }

    public function testCannotReadFileException()
    {
        $this->expectException(CannotReadFileException::class);
        getDiff(__DIR__ . '/fixtures/json/unknown_file.json', __DIR__ . '/fixtures/json/after.json');
    }

    public function testInvalidlyFormattedYamlFileException()
    {
        $this->expectException(InvalidFormattedFileException::class);
        getDiff(__DIR__ . '/fixtures/yaml/before.yaml', __DIR__ . '/fixtures/yaml/invalid.yaml');
    }

    public function testInvalidlyFormattedJsonFileException()
    {
        $this->expectException(InvalidFormattedFileException::class);
        getDiff(__DIR__ . '/fixtures/json/before.json', __DIR__ . '/fixtures/json/invalid.json');
    }

    public function testUnknownFileExtensionException()
    {
        $this->expectException(UnknownFileExtensionException::class);
        getDiff('file1.xml', __DIR__ . 'file2.xml');
    }
}
