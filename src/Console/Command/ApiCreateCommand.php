<?php
/**
 * ApiCreateCommand class  - ApiCreateCommand.php file
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
 * ApiCreateCommand class
 *
 * @package   Console\Command
 */
class ApiCreateCommand extends Command
{

    protected function configure()
    {
        $this
                ->setName('api:create')
                ->setDescription('Создать метод API')
                ->addArgument('name', InputArgument::REQUIRED, 'Название метода')
//                ->addArgument('name2', InputArgument::REQUIRED, 'Название метода')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $html = $this->template($name);
        print_r($html);
    }

    protected function template($name)
    {
        $class = preg_replace_callback( '/^\w|\.\w/', function ($matches) {
            return strtoupper($matches[0]);
        }, $name );
        $class = 'Api_' . str_replace('.', '_', $class);
        $content =  file_get_contents( __DIR__ . '/../../Api/class/default.php' );
        $content = preg_replace(array('/{{name}}/', '/Api_CLASSNAME/'), array($name, $class), $content);

        echo $content;
    }
}
