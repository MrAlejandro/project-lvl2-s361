<?php

namespace Differ;

use function Differ\FileParserFactory\getParser;

const FORMAT_ADDED = '+';
const FORMAT_REMOVED = '-';
const FORMAT_UNCHANGED = ' ';
const LINE_STATE_ADDED = 'added';
const LINE_STATE_REMOVED = 'removed';
const LINE_STATE_UNCHANGED = 'unchanged';
const LINE_STATE_FORMAT_MAP = [
    LINE_STATE_ADDED => '+',
    LINE_STATE_REMOVED => '-',
    LINE_STATE_UNCHANGED => ' ',
];

function getDiff(string $firstFile, string $secondFile): string
{
    $firstFileExtension = pathinfo($firstFile, PATHINFO_EXTENSION);
    if ($firstFileExtension !== pathinfo($secondFile, PATHINFO_EXTENSION)) {
        return '';
    }

    $parse = getParser($firstFileExtension);
    $before = $parse(file_get_contents($firstFile));
    $after = $parse(file_get_contents($secondFile));
    $ast = buildAST($before, $after);
    $diff = buildDiffFromAST($ast);

    return $diff;
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

function buildDiffFromAST(array $ast, int $level = 0)
{
    $offset = str_pad('', $level * 4, ' ');
    $diffLines = array_reduce($ast, function ($acc, $item) use ($level, $offset) {
        $format = LINE_STATE_FORMAT_MAP[$item['state']];
        $value = is_array($item['value']) ? buildDiffFromAST($item['value'], $level + 1) : $item['value'];
        $acc[] = sprintf('%s  %s %s: %s', $offset, $format, $item['name'], $value);
        return $acc;
    }, ['{']);

    $diffLines[] = sprintf('%s}', $offset);

    return implode(PHP_EOL, $diffLines);
}

function buildAST(array $before, array $after)
{
    $allPropertiesNames = array_unique(array_merge(array_keys($before), array_keys($after)));
    $ast = array_reduce($allPropertiesNames, function ($acc, $name) use ($before, $after) {
        $isKeyRemoved = !array_key_exists($name, $after);
        $isKeyAdded = !array_key_exists($name, $before);

        if ($isKeyAdded || $isKeyRemoved) {
            $value = $before[$name] ?? $after[$name];
            $acc[] = [
                'state' => $isKeyAdded ? LINE_STATE_ADDED : LINE_STATE_REMOVED,
                'name' => $name,
                'value' => is_array($value) ? buildAST($value, $value) : stringifyValue($value),
            ];
        } elseif ($before[$name] === $after[$name]) {
            $acc[] = [
                'state' => LINE_STATE_UNCHANGED,
                'name' => $name,
                'value' => is_array($before[$name])
                    ? buildAST($before[$name], $after[$name])
                    : stringifyValue($before[$name]),
            ];
        } else {
            if (is_array($before[$name]) && is_array($after[$name])) {
                $acc[] = [
                    'state' => LINE_STATE_UNCHANGED,
                    'name'  => $name,
                    'value' => buildAST($before[$name], $after[$name]),
                ];
            } elseif (is_array($before[$name])) {
                $acc[] = [
                    'state' => LINE_STATE_ADDED,
                    'name' => $name,
                    'value' => stringifyValue($after[$name])
                ];
                $acc[] = [
                    'state' => LINE_STATE_REMOVED,
                    'name'  => $name,
                    'value' => buildAST($before[$name], $before[$name]),
                ];
            } elseif (is_array($after[$name])) {
                $acc[] = [
                    'state' => LINE_STATE_ADDED,
                    'name' => $name,
                    'value' => buildAST($after[$name], $after[$name]),
                ];
                $acc[] = [
                    'state' => LINE_STATE_REMOVED,
                    'name'  => $name,
                    'value' => stringifyValue($before[$name])
                ];
            } else {
                $acc[] = [
                    'state' => LINE_STATE_ADDED,
                    'name' => $name,
                    'value' => stringifyValue($after[$name])
                ];
                $acc[] = [
                    'state' => LINE_STATE_REMOVED,
                    'name' => $name,
                    'value' => stringifyValue($before[$name])
                ];
            }
        }

        return $acc;
    }, []);

    return $ast;
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
