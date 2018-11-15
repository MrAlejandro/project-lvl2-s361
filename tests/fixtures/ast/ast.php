<?php

return [
    ['state' => 'added', 'name' => 'key', 'value' => 'value'],
    ['state' => 'removed', 'name' => 'key2', 'value' => 'value2'],
    ['state' => 'unchanged', 'name' => 'key3', 'value' => 'value3'],
    ['state' => 'added', 'name' => 'group', 'value' => [
        ['state' => 'unchanged', 'name' => 'key', 'value' => 'value'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
    ]],
    ['state' => 'removed', 'name' => 'group2', 'value' => [
        ['state' => 'unchanged', 'name' => 'key', 'value' => 'value'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
    ]],
    ['state' => 'unchanged', 'name' => 'group3', 'value' => [
        ['state' => 'unchanged', 'name' => 'key', 'value' => 'value'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
        ['state' => 'added', 'name' => 'key3', 'value' => 'value3'],
        ['state' => 'added', 'name' => 'group4', 'value' => [
            ['state' => 'unchanged', 'name' => 'key', 'value' => 'value'],
            ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
        ]],
        ['state' => 'removed', 'name' => 'group5', 'value' => [
            ['state' => 'unchanged', 'name' => 'key', 'value' => 'value'],
            ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
        ]],
        ['state' => 'unchanged', 'name' => 'group6', 'value' => [
            ['state' => 'added', 'name' => 'key', 'value' => 'value'],
            ['state' => 'removed', 'name' => 'key2', 'value' => 'value2'],
            ['state' => 'unchanged', 'name' => 'key3', 'value' => 'value3'],
            ['state' => 'unchanged', 'name' => 'group7', 'value' => [
                ['state' => 'added', 'name' => 'key', 'value' => 'value'],
                ['state' => 'removed', 'name' => 'key2', 'value' => 'value2'],
            ]],
        ]],
        ['state' => 'unchanged', 'name' => 'group8', 'value' => []],
    ]],
];
