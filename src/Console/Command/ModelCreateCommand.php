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
    private $className;
    private $tableName;

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
                ->addArgument('table', InputArgument::REQUIRED, 'название таблицы')
                ->addOption('input', 'i', InputOption::VALUE_OPTIONAL, 'входной файл модели')
                ->addOption('output', 'o', InputOption::VALUE_NONE, 'сохранить результат')
                ->addOption('database', 'd', InputOption::VALUE_OPTIONAL, 'база дфнных')
                ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'пользователь')
                ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'пароль')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $input->getArgument('table');
        $this->app = new \Fobia\Base\Application(CONFIG_DIR . '/config.php');

        if ($db = $input->getOption('database')) {
            $this->app['settings']['database.databse'] = $db ;
        }
        if ($user = $input->getOption('user')) {
            $this->app['settings']['database.user'] = $user ;
        }
        if ($pass = $input->getOption('password')) {
            $this->app['settings']['database.password'] = $pass ;
        }

        $this->tableName = $tableName;
        $this->className = $this->parseClassName($tableName);

        $schema = $this->parseColumns($tableName);

        if ($input->getOption('input')) {
            $file = $input->getOption('input');
        } else {
            $file = __DIR__ . '/model-default.tpl';
        }
        $text = $this->template($file, $schema);
        echo $text;
    }

    protected function parseClassName($tableName)
    {
        $chs       = explode('_', $tableName);
        $className = '';
        foreach ($chs as $ch) {
            $className .= strtoupper(substr($ch, 0, 1))
                    . strtolower(substr($ch, 1));
        }

        if (substr($className, -1) == 's') {
            $className = substr($className, 0, -1);
        }

        return $className;
    }

    protected function parseColumns($tableName)
    {
        $arr = array();
        $pri = null;
        $result  = $this->app->db->query("SHOW FULL COLUMNS FROM {$tableName}");
        $columns = $result->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($columns as $key => $value) {
            if ($value['key'] == "PRI") {
                $pri = $value['field'];
            }
            $columns[$key]['null'] = ($value['null'] == "YES") ? true : false;

            $arr[$value['field']] = array(
                'type' => $this->parseType($value['type']),
                'dbType' => $value['type'],
                'null' => ($value['null'] == "YES") ? 1 : 0,
                'default' => $value['default'],
                'comment' => $value['comment']
            );

            unset($columns[$key]['collation'], $columns[$key]['extra'],
                  $columns[$key]['privileges'], $columns[$key]['key']);
        }
        return array($pri, $arr);
    }

    protected function parseType($dbType)
    {
        $type = $dbType;
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
        return $type;
    }

    protected function template($file, $schema)
    {
        if ( ! file_exists($file)) {
            die("Error. No select table\n");
        }
        $text = file_get_contents($file);

        foreach ($schema[1] as $key => $value) {
            $property .= sprintf(" * @property %-10s $%-12s - %s\n", $value['type'],
                                 $key, $value['comment']);

            $rolle = "array('" . strtolower($value['type']) . "', '{$value['dbType']}', {$value['null']}";
            if ($value['default'] !== null) {
                $rolle .= ", '" . $value['default'] . "'";
            }
            $rolle .= "),";
            $rules .= sprintf("        %-15s => %-12s \n", "'{$key}'", $rolle);
        }

        $pattern = array(
            '/{{property}}/',
            '/( \* @property[^\n]+\n)/',
            '/static protected \$_rules = array\(([^\)])*\);/'
        );
        $text = preg_replace($pattern, array('@property id', '', 'static protected \$_rules = array(' . PHP_EOL . "    );"), $text);

        $text = preg_replace('/{{fileName}}/', $this->className, $text);
        $text = preg_replace('/{{className}}/', $this->className, $text);
        $text = preg_replace('/{{tableName}}/', $this->tableName, $text);
        $text = preg_replace('( \*\n \* @package)', "{$property}" . '$0', $text);
        $text = preg_replace('/(static protected \$_rules = array\()([^\)]*)(\);)/', '$1' . PHP_EOL . $rules . '    $3', $text);
        $text = preg_replace('/(static protected \$_primaryKey =)([^;]+)(;)/', '$1' . " '{$schema[0]}'" . '$3', $text);

        return $text;
    }

}