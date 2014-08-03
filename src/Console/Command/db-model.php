#!/usr/bin/php
<?php
// -----------------------------------------------------------------------------
// GENERATE MODEL FILE
// -----------------------------------------------------------------------------
require_once 'ezc/Base/base.php';
spl_autoload_register(
        function($className) {
            ezcBase::autoload($className);
        }
);

$input   = new ezcConsoleInput();
$options = array();

$option            = $input->registerOption(new ezcConsoleOption('h', 'help'));
$option->shorthelp = "Show this message.";
$options['help']   = $option;

$option              = $input->registerOption(new ezcConsoleOption('d',
                                                                   'database'));
$option->type        = ezcConsoleInput::TYPE_STRING;
$option->shorthelp   = "Database name.";
$options['database'] = $option;

$option            = $input->registerOption(new ezcConsoleOption('t', 'table'));
$option->type      = ezcConsoleInput::TYPE_STRING;
$option->shorthelp = "Table model.";
$options['table']  = $option;

$option            = $input->registerOption(new ezcConsoleOption('i', 'input'));
$option->type      = ezcConsoleInput::TYPE_STRING;
$option->shorthelp = "Input file class.";
$options['input']  = $option;

$option            = $input->registerOption(new ezcConsoleOption('o', 'output'));
$option->type      = ezcConsoleInput::TYPE_STRING;
$option->shorthelp = "Output file model.";
$options['output'] = $option;
// -----------------------------------------------------------------------------

// var_dump($_SERVER);
/*
$projectDir = $_SERVER['PWD'];
if ($projectDir) {
    $projectDir = str_replace(array('-', '_'), ';', basename($projectDir));
    $arr = explode(';', $projectDir);
    $projectDir = '';
    foreach ($arr as $ch) {
        $projectDir .= strtoupper(substr($ch, 0, 1)) . strtolower(substr($ch, 1));
    }
    if (substr($projectDir, -1) == 's') {
        $projectDir = substr($projectDir, 0, -1);
    }
    echo $projectDir;
}
*/


try {
    $input->process();
} catch (ezcConsoleOptionException $e) {
    die($e->getMessage());
}

if ($options['help']->value === true) {
    echo "Usage: " . basename(__FILE__) . " [options] [tables]\n";
    echo "Tables: list usage tables.\n";
    echo "Options: \n";
    foreach ($input->getOptions() as $option) {
        $t   = $option->short;
        if ($t)
            $t   = "-$t,";
        printf("%5s", $t);
        $msg = $option->long;
        if ($option->default !== null) {
            $msg .= " [{$option->default}]";
        }
        printf(" --%-19s", $msg);
        echo " " . $option->shorthelp . "\n";
    }
    exit;
}
// -----------------------------------------------------------------------------


if ( ! $options['database']->value) {
    die("Error. No select database\n");
}
if ( ! $options['table']->value) {
    die("Error. No select table\n");
}


$DB = new mysqli('localhost', 'root', '', $options['database']->value);
if (mysqli_connect_errno()) {
    die("Error. MySQL connect error. \n");
}
$DB->query("SET NAMES 'UTF8'");


$table   = $options['table']->value;
$columns = array();

$query  = "SHOW FULL COLUMNS FROM " . $table;
$result = $DB->query($query);
while ($row    = $result->fetch_array()) {
    $columns[] = $row;
}



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
            if ($type == 'tinyint(1)' ) {
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

    $property .= sprintf(" * @property %-10s $%-12s - %s\n", $type, $row[0],
                         $row[8]);
    // strpos($row[0], "id")
    if (substr($row[0], -2)  == 'id') {
        $rtype = 'id';
    }
    $rules .= sprintf("        %-15s => %-12s \n", "'{$row[0]}'",
                      "'" . strtolower($rtype) . "',");
}
$chs = explode('_', $table);
$className = '';
foreach ($chs as $ch) {
    $className .= strtoupper(substr($ch, 0, 1)) . strtolower(substr($ch, 1));
}
if (substr($className, -1) == 's') {
    $className = substr($className, 0, -1);
}

$projectName = 'Congress';

// ------------------------------------------------------------------



if ($f = $options['input']->value) {
    if (!file_exists($f)) {
        die("Error. No select table\n");
    }
    $text = file_get_contents($f); 

    $pattern = array(
        '/( \* @property[^\n]+\n)/',
       '/static protected \$_rules = array\(([^\)])*\);/'
    );

    $text = preg_replace($pattern, array('', 'static protected \$_rules = array('.PHP_EOL."    );"), $text);

    $text = preg_replace('( \*\n \* @package)', "{$property}" . '$0', $text);
    $text = preg_replace('/(static protected \$_rules = array\()([^\)]*)(\);)/', '$1' . PHP_EOL . $rules . '    $3', $text);

    if ($options['output']->value) {
        file_put_contents($f, $text);
    } else {
        echo $text;
    }
    // echo "\n <<< ============== >>>\n";
    exit();
}

//  ==================================

$template = <<<HTML
/**
 * {$className} class
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace {$projectName}\Model;

use \Fobia\Model;

/**
 * {$className} class - table $table
 *
 *
{$property} *
 * @package  {$projectName}.Model
 */
class {$className} extends Model
{

    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = '{$table}';

    static protected \$_rules = array(
$rules    );

}

HTML;


echo $template;
