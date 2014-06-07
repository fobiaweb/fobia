<?php
/**
 * GetCommand class  - GetCommand.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



/**
 * GetCommand class
 *
 * @package   Console.Command
 */
class GetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('get')
            ->setDescription('Выполнить контролер')
            ->addArgument('path', InputArgument::REQUIRED, 'Путь к контролеру')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('path');
        $text = 'GET: '.$name;

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}