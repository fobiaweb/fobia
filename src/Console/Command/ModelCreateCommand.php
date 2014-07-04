<?php
/**
 * ModelCreateCommand class  - ModelCreateCommand.php file
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
 * ModelCreateCommand class
 *
 * @package   Console.Command
 */
class ModelCreateCommand extends Command
{

    protected $user     = 'root';
    protected $pass     = '';
    protected $database = '';
    private $property;
    private $rules;
    private $className;

    /**
     *
     * @var \Fobia\Base\Application
     */
    protected $app;

    protected function configure()
    {
        $this
                ->setName('model:create')
                ->setDescription('Создать метод API')
                ->addArgument('table', InputArgument::REQUIRED,
                              'Название таблицы')
                ->addOption('input', 'i', InputOption::VALUE_OPTIONAL,
                            'Входной файл модели')
                ->addOption('output', 'o', InputOption::VALUE_NONE,
                            'сохранить результат')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $input->getArgument('table');
        $this->app = new \Fobia\Base\Application(CONFIG_DIR . '/config.php');
        
        $this->parse_property($tableName);
        $this->parse_classname($tableName);
        
        echo $this->property;
        echo $this->rules;
        echo $this->className;
    }

    protected function parse_classname($tableName)
    {
        $chs       = explode('_', $tableName);
        $className = '';
        foreach ($chs as $ch) {
            $className .= strtoupper(substr($ch, 0, 1)) . strtolower(substr($ch,
                                                                            1));
        }
        if (substr($className, -1) == 's') {
            $className = substr($className, 0, -1);
        }

        $this->className = $className;
    }

    protected function parse_property($tableName)
    {
        $columns = array();

        $result = $this->app->db->query("SHOW FULL COLUMNS FROM {$tableName}");
        $columns    = $result->fetchAll(\PDO::FETCH_NUM);

        $property = '';
        $rules    = '';
        foreach ($columns as $row) {
            $type = $row[1];
            $t    = strpos($type, "(");

            $type_tmp = $type;
            if ($t !== false) {
                $type_tmp = substr($type, 0, $t);
            }

            switch ($type_tmp) {
                case 'year':
                case 'int': $type = 'int';
                    break;
                case 'tinyint':
                    if ($type == 'tinyint(1)') {
                        $type = 'bool';
                    } else {
                        $type = 'int';
                    }
                    break;
                case 'varchar': $type = 'string';
                    break;

                case 'date': $type = 'Date';
                    break;
                case 'time': $type = 'Time';
                    break;
                case 'timestamp':
                case 'datetime': $type = 'DateTime';
                    break;
                case 'enum': $type = 'string';
                    break;
            }
            $rtype = $type;

            if ($row[0] == 'data') {
                $type  = 'array';
                $rtype = 'json';
            }

            $property .= sprintf(" * @property %-10s $%-12s - %s\n", $type,
                                 $row[0], $row[8]);
            // strpos($row[0], "id")
            if (substr($row[0], -2) == 'id') {
                $rtype = 'id';
            }
            $rules .= sprintf("        %-15s => %-12s \n", "'{$row[0]}'",
                              "'" . strtolower($rtype) . "',");
        }
        $this->rules    = $rules;
        $this->property = $property;
    }





    protected function template()
    {
        if ($f = $options['input']->value) {
            if ( ! file_exists($f)) {
                die("Error. No select table\n");
            }
            $text = file_get_contents($f);

            $pattern = array(
                '/( \* @property[^\n]+\n)/',
                '/static protected \$_rules = array\(([^\)])*\);/'
            );

            $text = preg_replace($pattern,
                                 array('', 'static protected \$_rules = array(' . PHP_EOL . "    );"),
                                 $text);

            $text = preg_replace('( \*\n \* @package)', "{$property}" . '$0',
                                 $text);
            $text = preg_replace('/(static protected \$_rules = array\()([^\)]*)(\);)/',
                                 '$1' . PHP_EOL . $rules . '    $3', $text);

            if ($options['output']->value) {
                file_put_contents($f, $text);
            } else {
                echo $text;
            }
            // echo "\n <<< ============== >>>\n";
            exit();
        }
    }
}