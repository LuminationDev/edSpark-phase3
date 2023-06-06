<?php
namespace App\Helpers;

use Symfony\Component\Console\Output\ConsoleOutput;

class OutputHelper {

    public static function print($message): void
    {
        $output = new ConsoleOutput();
        $output->writeln($message);
    }
}