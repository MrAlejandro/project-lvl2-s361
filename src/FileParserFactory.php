<?php

namespace Differ\FileParserFactory;

use Symfony\Component\Yaml\Yaml;

function getParser($extension): callable
{
    switch ($extension) {
        case 'yaml':
            return getYamlFileParser();
        case 'json':
            return getJsonFileParser();
        default:
            return function () {
                return [];
            };
    }
}

function getYamlFileParser(): \Closure
{
    return function ($yaml) {
        return Yaml::parse($yaml);
    };
}

function getJsonFileParser(): \Closure
{
    return function ($json) {
        return json_decode($json, true);
    };
}
