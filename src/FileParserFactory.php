<?php

namespace FileParserFactory;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Exceptions\InvalidFormattedFileException;
use Exceptions\UnknownFileExtensionException;

function getParser($extension): callable
{
    switch ($extension) {
        case 'yaml':
            return getYamlFileParser();
        case 'json':
            return getJsonFileParser();
    }

    throw new UnknownFileExtensionException($extension);
}

function getYamlFileParser(): \Closure
{
    return function ($yaml) {
        try {
            return Yaml::parse($yaml);
        } catch (ParseException $e) {
            throw new InvalidFormattedFileException();
        }
    };
}

function getJsonFileParser(): \Closure
{
    return function ($json) {
        $decoded = json_decode($json, true);
        if ($decoded === null) {
            throw new InvalidFormattedFileException();
        }

        return $decoded;
    };
}
