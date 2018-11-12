<?php

namespace Differ;

function getDiff(string $filePath1, string $filePath2)
{
    $data1 = readArrayFromJsonFile($filePath1);
    $data2 = readArrayFromJsonFile($filePath2);
    return generateDiff($data1, $data2);
}

function readArrayFromJsonFile($filePath)
{
    return json_decode(file_get_contents($filePath), true);
}

function generateDiff(array $data1, array $data2)
{
    $uniqueKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $diffStrings = array_reduce($uniqueKeys, function ($acc, $key) use ($data1, $data2) {
        $value = $data1[$key] ?? $data2[$key];
        if (!array_key_exists($key, $data2)) {
            $acc[] = sprintf('  - %s: %s', $key, stringifyValue($value));
        } elseif (!array_key_exists($key, $data1)) {
            $acc[] = sprintf('  + %s: %s', $key, stringifyValue($value));
        } elseif ($data1[$key] === $data2[$key]) {
            $acc[] = sprintf('    %s: %s', $key, stringifyValue($value));
        } else {
            $acc[] = sprintf('  + %s: %s', $key, stringifyValue($data2[$key]));
            $acc[] = sprintf('  - %s: %s', $key, stringifyValue($data1[$key]));
        }
        return $acc;
    }, ['{']);
    $diffStrings[] = '}';

    return implode(PHP_EOL, $diffStrings);
}

function stringifyValue($value): string
{
    $stringValue = $value;
    if (is_bool($value)) {
        $stringValue = $value ? 'true' : 'false';
    }

    return $stringValue;
}
