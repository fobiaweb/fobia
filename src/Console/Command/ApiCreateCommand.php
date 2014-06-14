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
                ->addArgument('name2', InputArgument::REQUIRED, 'Название метода');
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
        return <<<HTML
<?php
/**
 * {$name}.php file
 *
 * Название метода
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 *
 * --------------------------------------------
 *
 * RESULT:
 * ------
 * Возвращаемый результат
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * @api
 */

/* @var \$this   \Api\ApiMethod */
/* @var \$params array */

if (! \$this instanceof \Api\ApiMethod) {
    throw new \Exception('Нельзя прос так выполнить этот файл');
}


\$db = \$this->app->db;


\$this->response = 1;
return;

HTML;
    }
}