<?php

namespace App;

use Docopt\Handler;

class App
{
    public function run()
    {
        $doc = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

DOC;

        $args = (new Handler)->handle($doc);

        /* // long form, simple API (equivalent to short) */
        /* $params = array( */
        /*     'argv'=>array_slice($_SERVER['argv'], 1), */
        /*     'help'=>true, */
        /*     'version'=>null, */
        /*     'optionsFirst'=>false, */
        /* ); */
        /* $args = Docopt::handle($doc, $params); */
        var_dump($args);

        // long form, full API
        /* $handler = new Handler(array( */
        /*     'help'=>true, */
        /*     'optionsFirst'=>false, */
        /* )); */
        /* $handler->handle($doc, $argv); */
    }
}
