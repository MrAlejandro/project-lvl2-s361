<?php

namespace Differ;

const FORMAT_ADDED = '+';
const FORMAT_REMOVED = '-';
const FORMAT_UNCHANGED = ' ';

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
        $isKeyRemoved = !array_key_exists($key, $data2);
        $isKeyAdded = !array_key_exists($key, $data1);
        if ($isKeyAdded || $isKeyRemoved) {
            $acc[] = formatDiffString($key, $value, $isKeyAdded ? FORMAT_ADDED : FORMAT_REMOVED);
        } elseif ($data1[$key] === $data2[$key]) {
            $acc[] = formatDiffString($key, $value, FORMAT_UNCHANGED);
        } else {
            $acc[] = formatDiffString($key, $data2[$key], FORMAT_ADDED);
            $acc[] = formatDiffString($key, $data1[$key], FORMAT_REMOVED);
        }
        return $acc;
    }, ['{']);
    $diffStrings[] = '}';

    return implode(PHP_EOL, $diffStrings);
}
function formatDiffString($key, $value, $format)
{
    return sprintf('  %s %s: %s', $format, $key, stringifyValue($value));
}

function stringifyValue($value): string
{
    $stringValue = $value;
    if (is_bool($value)) {
        $stringValue = $value ? 'true' : 'false';
    } elseif (is_null($value)) {
        $stringValue = 'null';
    }

    return $stringValue;
}
