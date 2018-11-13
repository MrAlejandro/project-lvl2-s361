<?php

namespace Differ;

use function FileParserFactory\getParser;

const FORMAT_ADDED = '+';
const FORMAT_REMOVED = '-';
const FORMAT_UNCHANGED = ' ';

function getDiff(string $firstFile, string $secondFile): string
{
    $parse = getParser($firstFile, $secondFile);
    $data1 = $parse($firstFile);
    $data2 = $parse($secondFile);
    return generateDiffString($data1, $data2);
}

function generateDiffString(array $before, array $after): string
{
    $allPropertiesNames = array_unique(array_merge(array_keys($before), array_keys($after)));

    $diffStrings = array_reduce($allPropertiesNames, function ($acc, $name) use ($before, $after) {
        $isKeyRemoved = !array_key_exists($name, $after);
        $isKeyAdded = !array_key_exists($name, $before);

        if ($isKeyAdded || $isKeyRemoved) {
            $value = $before[$name] ?? $after[$name];
            $acc[] = formatDiffString($name, $value, $isKeyAdded ? FORMAT_ADDED : FORMAT_REMOVED);
        } elseif ($before[$name] === $after[$name]) {
            $acc[] = formatDiffString($name, $before[$name], FORMAT_UNCHANGED);
        } else {
            $acc[] = formatDiffString($name, $after[$name], FORMAT_ADDED);
            $acc[] = formatDiffString($name, $before[$name], FORMAT_REMOVED);
        }

        return $acc;
    }, ['{']);
    $diffStrings[] = '}';

    return implode(PHP_EOL, $diffStrings);
}
function formatDiffString($key, $value, $format): string
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
