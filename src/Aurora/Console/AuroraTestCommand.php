<?php

namespace App\Aurora\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuroraTestCommand extends Command
{

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('hello:aurora')

            // the short description shown while running "php bin/console list"
            ->setDescription('Echo hello aurora')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to write hello aurora to screen')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello Aurora');
    }
}