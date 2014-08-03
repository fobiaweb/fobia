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
use Symfony\Component\Console\Formatter\OutputFormatterStyle;



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

        if ($output->isDebug()) {
            $formatter = $this->getHelperSet()->get('formatter');
            $formattedLine = $formatter->formatSection(
                'SomeSection',
                'Here is some message related to that section'
            );
            $output->writeln($formattedLine);

            $errorMessages = array('Error!', 'Something went wrong');
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);

            // green text
            $output->writeln('<info>foo</info>');

            // yellow text
            $output->writeln('<comment>foo</comment>');

            // black text on a cyan background
            $output->writeln('<question>foo</question>');

            // white text on a red background
            $output->writeln('<error>foo</error>');

            $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
            $output->getFormatter()->setStyle('fire', $style);
            $output->writeln('<fire>foo</fire>');

            // green text
            $output->writeln('<fg=green>foo</fg=green>');

            // black text on a cyan background
            $output->writeln('<fg=black;bg=cyan>foo</fg=black;bg=cyan>');

            // bold text on a yellow background
            $output->writeln('<bg=yellow;options=bold>foo</bg=yellow;options=bold>');
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

        include HTML_DIR . '/index.php';
    }
}