<?php
/**
 * ApiSearchCommand class  - ApiSearchCommand.php file
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
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * ApiSearchCommand class
 *
 * @package   
 */
class ApiSearchCommand extends Command
{

    protected $dir = 'app/classes/api';

    protected function configure()
    {
        $this
                ->setName('api:search')
                ->setDescription('Создать метод API')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $r = shell_exec("grep -Hr '@api' app/classes/");
        $arr = explode("\n", trim($r));


        preg_match_all('/([^:]+):([^\n]+)\n?/', $r, $m);
        print_r($m);
    }

}