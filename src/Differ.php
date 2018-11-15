<?php

namespace Differ;

use function Differ\FileParserFactory\getParser;

const FORMAT_ADDED = '+';
const FORMAT_REMOVED = '-';
const FORMAT_UNCHANGED = ' ';
const LINE_STATE_FORMAT_MAP = [
    'added' => '+',
    'removed' => '-',
    'unchanged' => ' ',
];

function getDiff(string $firstFile, string $secondFile): string
{
    $firstFileExtension = pathinfo($firstFile, PATHINFO_EXTENSION);
    if ($firstFileExtension !== pathinfo($secondFile, PATHINFO_EXTENSION)) {
        return '';
    }

    $parse = getParser($firstFileExtension);
    $data1 = $parse(file_get_contents($firstFile));
    $data2 = $parse(file_get_contents($secondFile));

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

function buildDiffFromAst(array $ast, int $level = 0)
{
    $offset = str_pad('', $level * 4, ' ');
    $diffLines = array_reduce($ast, function ($acc, $item) use ($level, $offset) {
        $format = LINE_STATE_FORMAT_MAP[$item['state']];
        $value = is_array($item['value']) ? buildDiffFromAst($item['value'], $level + 1) : $item['value'];
        $acc[] = sprintf('%s  %s %s: %s', $offset, $format, $item['name'], $value);
        return $acc;
    }, ['{']);

    $diffLines[] = sprintf('%s}', $offset);

    return implode(PHP_EOL, $diffLines);
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
