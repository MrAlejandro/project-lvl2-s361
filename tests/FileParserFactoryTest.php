<?php

use PHPUnit\Framework\TestCase;
use Exceptions\UnknownFileExtensionException;

use function FileParserFactory\getParser;

class FileParserFactoryTest extends TestCase
{
    public function testUnknownFileExtensionException()
    {
        $this->expectException(UnknownFileExtensionException::class);
        getParser('dummy');
    }
}
