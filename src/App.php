<?php

namespace App;

use Docopt\Handler;
use Exceptions\DiffFilesExtensionMismatchException;
use Exceptions\InvalidFormattedFileException;
use Exceptions\UnknownFileExtensionException;
use Exceptions\CannotReadFileException;

use function Differ\getDiff;

function run()
{
    try {
        $args = (new Handler())->handle(getDoc());
        echo getDiff($args['<firstFile>'], $args['<secondFile>']);
    } catch (DiffFilesExtensionMismatchException $e) {
        echo 'Cannot generate diff for two files of different types';
    } catch (InvalidFormattedFileException $e) {
        echo sprintf('Invalidly formatted file supplied %s', $e->getMessage());
    } catch (UnknownFileExtensionException $e) {
        echo sprintf('Unknown file format %s', $e->getMessage());
    } catch (CannotReadFileException $e) {
        echo sprintf('Cannot read file %s', $e->getMessage());
    } finally {
        echo PHP_EOL;
    }
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
