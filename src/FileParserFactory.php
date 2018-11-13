<?php

namespace FileParserFactory;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Exceptions\DiffFilesExtensionMismatchException;
use Exceptions\InvalidFormattedFileException;
use Exceptions\UnknownFileExtensionException;
use Exceptions\CannotReadFileException;

function getParser(...$files): callable
{
    $extension = getCommonFilesExtensionOrThrowException($files);
    switch ($extension) {
        case 'yaml':
            return getYamlFileParser();
        case 'json':
            return getJsonFileParser();
    }

    throw new UnknownFileExtensionException($extension);
}

function getCommonFilesExtensionOrThrowException($files): string
{
    $extensions = array_map(function ($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }, $files);

    $isSameExtension = count(array_unique($extensions)) === 1;
    if (!$isSameExtension) {
        throw new DiffFilesExtensionMismatchException();
    }

    return $extensions[0];
}

function getYamlFileParser(): \Closure
{
    return function ($filePath) {
        throwExceptionIfFileNotReadable($filePath);
        try {
            return Yaml::parseFile($filePath);
        } catch (ParseException $e) {
            throw new InvalidFormattedFileException($filePath);
        }
    };
}

function throwExceptionIfFileNotReadable(string $filePath)
{
    if (!file_exists($filePath) || !is_readable($filePath)) {
        throw new CannotReadFileException($filePath);
    }
}

function getJsonFileParser(): \Closure
{
    return function ($filePath) {
        throwExceptionIfFileNotReadable($filePath);
        $decoded = json_decode(file_get_contents($filePath), true);
        if ($decoded === null) {
            throw new InvalidFormattedFileException($filePath);
        }

        return $decoded;
    };
}
