<?php

namespace App;

use Docopt\Handler;

function run()
{
    $args = (new Handler)->handle(getDoc());
}

function getDoc()
{
    return <<<DOC
Generate diff

Usage:
    gendiff [--format <fmt>] <firstFile> <secondFile>
    gendiff (-h|--help)

Options:
    -h --help                     Show this screen
    --format <fmt>                Report format [default: pretty]

DOC;
}
