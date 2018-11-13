<?php

namespace Differ;

const FORMAT_ADDED = '+';
const FORMAT_REMOVED = '-';
const FORMAT_UNCHANGED = ' ';

function getDiff(string $filePath1, string $filePath2)
{
    $data1 = json_decode(file_get_contents($filePath1), true);
    $data2 = json_decode(file_get_contents($filePath2), true);
    return generateDiffString($data1, $data2);
}

function generateDiffString(array $before, array $after)
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
