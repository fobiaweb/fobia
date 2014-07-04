<?php
/**
 * Method class  - Method.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api\Method;

/**
 * Method class
 *
 * @package   Api.Method
 */
abstract class Method
{

    const VALUE_NONE     = 1;
    const VALUE_REQUIRED = 2;
    const VALUE_OPTIONAL = 4;
    const VALUE_IS_ARRAY = 8;

    /**
     * Переданые параметры
     */
    private $params;

    /**
     * определение параметров
     */
    private $definition;
    private $definitionMergedWithArgs;
    private $ignoreValidationErrors = false;
    protected $name;
    protected $method;
    protected $response;

    /**
     * @internal
     */
    public function __construct($params = null)
    {
        $this->params = (array) $params;
        $this->definition = array();
        $this->ignoreValidationErrors = array();

        $this->configure();

    }

    /**
     * @internal
     */
    public function __invoke()
    {
        $this->dispatchMethod('invoke', func_get_args());
        return $this->getFormatResponse();
    }

    /**
     * Выполнить метод
     *
     * @return boolean  флаг об успешности выполнения метода
     */
    public function invoke()
    {
        $this->exc      = null;
        $this->response = null;
        \Log::info("[API]:: Вызов метода '$this->method' - ", $this->params);

        try {
            $this->initialize();
            $this->dispatchMethod('execute', func_get_args());
            return true;
        } catch (\Api\Exception\Halt $exc) {
            return true;
        } catch (\Api\Exception\Error $exc) {
            $this->exc = $exc;
            \Log::error("[API]:: (" . get_class($exc) . ") " . $exc->getMessage());
            return false;
        } catch (\Exception $exc) {
            \Log::error("[API]:: (" . get_class($exc) . ") " . $exc->getMessage());
            return false;
        }
    }

    /**
     * Возвращает результат
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Возвращает форматированный результат
     *
     * @return array
     */
    public function getFormatResponse()
    {
        if ($this->exc === null) {
            return array('response' => $this->response);
        } else {
            return array(
                'error' => $this->errorInfo()
            );
        }
    }

    /**
     * Информация об ошибки
     *
     * @return null|array
     */
    public function errorInfo()
    {
        if ($this->exc !== null) {
            return array(
                'err_msg'  => $this->exc->getMessage(),
                'err_code' => $this->exc->getCode(),
                'method'   => $this->method,
                'params'   => $this->params
            );
        } else {
            return null;
        }
    }

    /**
     * Ignores validation errors.
     *
     * This is mainly useful for the help command.
     */
    public function ignoreValidationErrors()
    {
        $this->ignoreValidationErrors = true;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {

    }

    /**
     * Initializes the command just after the input has been validated.
     */
    protected function initialize()
    {
        $params     = $this->params;
        $definition = $this->definition;

        $args = array();
        foreach ($definition as $key => $value) {
            if (key_exists($key, $params)) {
                $args[$key] = $params[$key];
            } else {
                if ($value['default'] !== null) {
                    $args[$key] = $value['default'];
                } else if ($value['mode'] == self::VALUE_REQUIRED) {
                    throw new \Api\Exception\BadRequest($key);
                }
                continue;
            }

            foreach ((array) $value['parse'] as $cb) {
                $callback_args = array();
                if (is_array($cb)) {
                    $callback_args = $cb;
                    $cb = array_shift($callback_args);
                    array_unshift($callback_args, $args[$key]);
                } else {
                    $callback_args = array($args[$key]);
                }
                
                if (is_callable($cb)) {
                    $args[$key] = call_user_func_array($cb, $callback_args);
                } else {
                    throw new \Api\Exception\ServerError('Не верный формат callable - ' . $cb);
                }
            }
            foreach ((array) $value['assert'] as $cb) {
                if (is_callable($cb)) {
                    if ( ! $cb($args[$key])) {
                        throw new \Api\Exception\BadRequest($key);
                    }
                } else {
                    throw new \Api\Exception\ServerError('Не верный формат callable - ' . $cb);
                }
            }
        }

        $this->definitionMergedWithArgs =  $args ;
    }

    /**
     * Устанавить атрибуты параметру
     *
     * <pre>
     * mode     - режим
     * default  - по умолчанию
     * parse    - парсировка и разборка
     * assert   - проверка валидности
     * </pre>
     *
     * @param string $name
     * @param array $options
     */
    protected function setDefinition($name, array $options)
    {
        $options_default = array(
            'mode' => self::VALUE_NONE,
            'default' => null,
            'parse' => null,// array(),
            'assert' => null, // array(),
        );
        $this->definition[$name] = array_merge($options_default, $options);
    }

    protected function getDefinition()
    {
        return $this->definition;
    }

    protected function getDefinitionParams()
    {
        return $this->definitionMergedWithArgs;
    }

    protected function getParams()
    {
        return $this->params;
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    protected function getName()
    {
        return $name;
    }

    abstract protected function execute();

    /**
     * Установка/получение параметра
     *
     * # // возвращает параметр
     * # params->params('name')
     *
     * # // устанавливает параметр
     * # params->params('name', 'value')
     *
     * # // обнуляет масив и устанавливает новые параметры
     * # params->params(array('name1', 'name2'))
     *
     *
     * @param array|string $name
     * @param array|string $value
     *
     * @return array|string
     */
    protected function params($name = null, $value = null)
    {
        $n = func_num_args();

        switch ($n) {
            case 0:
                return $this->params;
            case 1:
                if (is_array($name)) {
                    $this->params = $name;
                    return;
                }
                return $this->params[$name];
            case 2:
            default:
                $this->params[$name] = $value;
                return;
        }
    }

    /**
     * ХАК к вызову метода.
     * Аналог 'call_user_func_array', но работает быстрее
     *
     * @param string $method
     * @param array $p
     * @return mixed
     */
    protected function dispatchMethod($method, array $p = null)
    {
        $o = $this;
        switch (@count($p)) {
            case 0: return $o->{$method}();
            case 1: return $o->{$method}($p[0]);
            case 2: return $o->{$method}($p[0], $p[1]);
            case 3: return $o->{$method}($p[0], $p[1], $p[2]);
            case 4: return $o->{$method}($p[0], $p[1], $p[2], $p[3]);
            case 5: return $o->{$method}($p[0], $p[1], $p[2], $p[3], $p[4]);
            default: return call_user_func_array(array($o, $method), $p);
        }
    }
}