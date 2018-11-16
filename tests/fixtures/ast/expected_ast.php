<?php

return [
    ['state' => 'removed', 'name' => 'key', 'value' => 'value'],
    ['state' => 'added', 'name' => 'key', 'value' => 'another_value'],
    ['state' => 'removed', 'name' => 'key2', 'value' => 'value2'],
    ['state' => 'unchanged', 'name' => 'key3', 'value' => 'value3'],
    ['state' => 'removed', 'name' => 'group1', 'value' => [
        ['state' => 'unchanged', 'name' => 'key1', 'value' => 'value1'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
    ]],
    ['state' => 'unchanged', 'name' => 'group2', 'value' => [
        ['state' => 'removed', 'name' => 'key1', 'value' => 'value1'],
        ['state' => 'added', 'name' => 'key1', 'value' => 'another_value'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
        ['state' => 'removed', 'name' => 'key3', 'value' => 'value3'],
        ['state' => 'added', 'name' => 'key4', 'value' => 'value4'],
    ]],
    ['state' => 'removed', 'name' => 'group4', 'value' => [
        ['state' => 'unchanged', 'name' => 'key1', 'value' => 'value1'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
    ]],
    ['state' => 'added', 'name' => 'group4', 'value' => 'shit_happens'],
    ['state' => 'removed', 'name' => 'key5', 'value' => 'value5'],
    ['state' => 'added', 'name' => 'key5', 'value' => [
        ['state' => 'unchanged', 'name' => 'key1', 'value' => 'ama_group_now'],
    ]],
    ['state' => 'added', 'name' => 'key4', 'value' => 'value4'],
    ['state' => 'added', 'name' => 'group3', 'value' => [
        ['state' => 'unchanged', 'name' => 'key1', 'value' => 'value1'],
        ['state' => 'unchanged', 'name' => 'key2', 'value' => 'value2'],
    ]],
];
