<?php
/**
 * ObjectCollection class  - ObjectCollection.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2013 AC Software
 */

namespace Fobia\Base;

/**
 * Колекция однородных объектов
 *
 * @package		Fobia.Base
 */
class ObjectCollection implements \IteratorAggregate, \Countable
{

    /** @var array */
    protected $data = array();

    /** @var int */
    private $_count = 0;

    /**
     * @internal
     */
    public function __construct(array $data = array())
    {
        if (count($data)) {
            $this->merge($data);
        }
        $this->_resor(false);
    }

    /**
     * Выбрать непосредственно экземпляр объекта.
     *
     * @param int $index индекс объекта
     * @return \stdObject
     */
    public function eq($index = 0)
    {
        return $this->data[$index];
    }

    /**
     * Найти все элементы, параметр которых удовлетворяют услови.
     *
     * ### Поиск объектов с существующим свойством
     * e.g `find('Location');`
     *
     * ### Поиск объектов со свойством равным указаному значению
     * e.g `find('Location', 'localhost/js');`
     *
     * ### Поиск объектов удавлетворяющие возврату йункции
     * e.g `find('Location', function($name_value, $obj, $args), $args);`
     *
     * @param string   $name       название свойства
     * @param mixed    $param      его значение или функция обратного вызова.
     *                             в функцию передаеться [значение поля, оъект, $args]
     * @param mixed    $args       дополнительные параметры, переданные в функцию
     *                             обратного вызова.
     * @return self  колекция найденных объектов.
     */
    public function find($name, $param = null, $args = null)
    {
        $data = array();

        // Существавание свойства
        if (func_num_args() == 1) {
            foreach ($this->data as $obj) {
                if (isset($obj->$name)) {
                    $data[] = $obj;
                }
            }
        }

        if (func_num_args() > 1) {
            //$args = func_get_args();
            //$args = $args[2];
            // Сравнение свойства
            foreach ($this->data as $obj) {
                // Функция обратного вызова
                if (is_callable($param)) {
                    if ($param($obj->$name, $obj, $args)) {
                        $data[] = $obj;
                    }
                } else {
                    if ($obj->$name == $param) {
                        $data[] = $obj;
                    }
                }
            }
        }

        return new self($data);
    }

    /**
     * Установить свойства в новое значение.
     *
     * @param string   $name    имя свойства
     * @param mixed    $value   устанавливаемое значение
     * @return self
     */
    public function set($name, $value)
    {
        foreach ($this->data as $obj) {
            $obj->$name = $value;
        }

        return $this;
    }

    /**
     * Выбрать значения свойсвта из списка.
     *
     * @param string $name
     * @return array
     */
    public function get($name)
    {
        $data = array();
        foreach ($this->data as $obj) {
            $data[] = $obj->$name;
        }

        return $data;
    }

    /**
     * Добавить объект в коллекцию.
     *
     * @param stdObject   $object    позиция
     * @param int         $index     позиция
     * @return self
     */
    public function addAt($object, $index = null)
    {
        if ( ! is_object($object)) {
            $object = (object) $object;
        }

        if ($index === null) {
            array_push($this->data, $object);
        } elseif ($index === 0) {
            array_unshift($this->data, $object);
        } else {
            $arr = array();
            for ($i = 0; $i < $this->_count; $i ++ ) {
                if ($i == $index) {
                    array_push($arr, $object);
                }
                array_push($arr, $this->data[$i]);
            }
            $this->data = $arr;
        }
        $this->_resor(false);

        return $this;
    }

    /**
     * Сливает масив объектов в текущюю колекцию
     *
     * @param array|ObjectCollection $data
     * @return self
     */
    public function merge($data)
    {
        if ($data instanceof ObjectCollection) {
            $data = $data->toArray();
        }

        if (is_array($data)) {
            array_walk($data,
                       function(&$value) {
                $value = (object) $value;
            });
            $this->data  = array_merge($this->data, $data);
            $this->_resor(false);
        } else {
            $this->addAt($data);
        }
        return $this;
    }

    /**
     * Удалить объект с указаной позиции из колеции.
     *
     * @param  int   $index    позиция
     * @return self
     */
    public function removeAt($index = null)
    {
        if ($index === null) {
            $index = $this->_count - 1;
        }

        unset($this->data[$index]);
        $this->_resor();

        return $this;
    }

    /**
     * Удалить объект.
     *
     * @param mixed $object
     * @return self
     */
    public function remove($object)
    {
        $keys = array_keys($object, $this->data, true);
        foreach ($keys as $key) {
            unset($this->data[$key]);
        }
        $this->_resor();
        return $this;
    }

    /**
     * Обходит весь масив, передавая функции объект, его индекс и дополнительные параметры.
     * Если функция возвращает false, обход останавливаеться.
     *
     * @param callback $callback
     * @param mixed    $args
     * @return self
     */
    public function each($callback, $args = null)
    {
        if ( ! is_callable($callback)) {
            trigger_error("CORE: Параметр не является функцией обратного вызова.",
                          E_USER_ERROR);
        }

        foreach ($this->data as $key => $obj) {
            if ($callback($obj, $key, $args) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Сортирует список, используя функцию обратного вызова либо по полю.
     *
     * @param callback|string $param  int callback ( mixed $a, mixed $b )
     * @param mixed    $args
     * @return self
     */
    public function sort($param, $args = null)
    {
        if (is_callable($param)) {
            usort($this->data, $param($args));
        } else {
            usort($this->data, $this->_sort_property($param));
        }

        return $this;
    }

    /**
     * Пересобрать список объектов
     *
     * @return int   количество элементов
     */
    protected function _resor($resor_values = true)
    {
        if ($resor_values) {
            $this->data = array_values($this->data);
        }
        $this->_count = count($this->data);
        return $this->_count;
    }

    /**
     * Сортировка по свойству
     *
     * @param string $key
     * @return int
     */
    protected function _sort_property($key)
    {
        if ( ! $key) {
            return 0;
        }
        return function($a, $b) use($key) {
            return strnatcmp($a->$key, $b->$key);
        };
    }
    /**
     * ---------------------------
     *
     * ---------------------------
     */

    /**
     * Количество объектов
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * @internal
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Масив объектов колекции
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}