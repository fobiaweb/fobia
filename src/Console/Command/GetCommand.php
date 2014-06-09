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
        $text = 'GET: ' . $name;

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }


        // $app = $this->getApplication()->createApp(array(
        //     // 'REQUEST_METHOD' => 'GET',
        //     // 'SCRIPT_NAME' => '/fobia/index.php',
        //     'REQUEST_URI' => '/fobia' . $name , ///action/test?f=11&t=4',
        //     'QUERY_STRING' => '' // 'f=11&t=4'
        // ));
        // $output->writeln($text);
        // $app->run();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SCRIPT_NAME'] = '/fobia/index.php';
        $_SERVER['REQUEST_URI'] = '/fobia' . $name ;
        $_SERVER['QUERY_STRING'] = '';// . $name ;
        require_once '/srv/fobiaweb/fobia/web/index.php';
    }
}