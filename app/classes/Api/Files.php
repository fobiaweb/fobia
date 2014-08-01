<?php

/**
 * Files class  - Files.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Api\Method\Method;

/**
 * Files class
 *
 * @package   Api
 */
class Api_Files extends Method
{

    protected function configure()
    {
        $map = array(
            'files.add'    => array('object', 'Api_Files'),
            'files.delete' => array('object', 'Api_Files'),
            'files.edit'   => array('object', 'Api_Files'),
        );

        $this->setDefinition(array(
            'name' => 'id',
            'mode' => Method::VALUE_REQUIRED,
        ));
        $this->setDefinition(array(
            'name' => 'file',
            'mode' => Method::VALUE_REQUIRED,
        ));
        $this->setDefinition(array(
            'name'  => 'type',
            'parse' => 'trim'
        ));
        $this->setDefinition(array(
            'name'  => 'data_id',
            'parse' => 'parsePositive'
        ));
        $this->setDefinition(array(
            'name'  => 'title',
            'parse' => 'trim'
        ));
    }

    protected function execute()
    {

    }

    public function add()
    {
        $this->setName("files.add");
    }

    public function edit()
    {
        $this->setName("files.edit");
    }

    public function delete()
    {
        $this->setName("files.delete");
    }
}