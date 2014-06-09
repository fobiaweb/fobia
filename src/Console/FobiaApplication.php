<?php
/**
 * FobiaApplication class  - FobiaApplication.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;


use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;



/**
 * FobiaApplication class
 *
 * @package Console.FobiaApplication
 */
class FobiaApplication  extends Application
{
    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     *
     * @return string The command name
     */
//    protected function getCommandName(InputInterface $input)
//    {
        // This should return the name of your command.
//        return 'help';
//    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
//    protected function getDefaultCommands()
//    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        // $defaultCommands = parent::getDefaultCommands();

//        $defaultCommands[] = new Command\GetCommand();
//
//        return $defaultCommands;
//    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        // $inputDefinition->setArguments();

        return $inputDefinition;
    }


    public function createApp(array $envSettings = array(), array $appSettings = array())
    {
        $envSettings = array_merge(array(
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/fobia/index.php',
            'REQUEST_URI' => '/fobia/action/test?f=11&t=4',
            'QUERY_STRING' => 'f=11&t=4'
        ), $envSettings);
        $appSettings = array_merge(array(), $appSettings);
        $app = new \Fobia\Base\Application($appSettings);
        return $app;
    }



    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            // new InputOption('--help',           '-h', InputOption::VALUE_NONE, 'Display this help message.'),
            new InputOption('--quiet',          '-q', InputOption::VALUE_NONE, 'Do not output any message.'),
            new InputOption('--verbose',        '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages'),
            new InputOption('--version',        '-V', InputOption::VALUE_NONE, 'Display this application version.'),
            // new InputOption('--[no-]ansi',           '',   InputOption::VALUE_NONE, 'Force ANSI output.'),
            // new InputOption('--no-ansi',        '',   InputOption::VALUE_NONE, 'Disable ANSI output.'),
            // new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question.'),
        ));
    }
}