<?php

namespace Fobia\Base;

class MyModel extends Model
{

    const CLASS_NAME = __CLASS__;
    const TABLE_NAME = 'users';

    static protected $_primaryKey = 'id';
    static protected $_rules      = array(
        'id'        => 'int',
        'login'     => 'string',
        'password'  => 'string',
        'role_mask' => 'int',
        'online'    => 'datetime',
        'sid'       => 'string',
    );
}

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-06 at 14:27:20.
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MyModel
     */
    protected $model;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->model = new MyModel();
    }

    /**
     * @covers Fobia\Base\Model::rules
     * @todo   Implement testRules().
     */
    public function testRules()
    {
        $rules = $this->model->rules();
        $this->assertArrayHasKey('id', $rules);
        $this->assertArrayHasKey('login', $rules);

        $this->assertInternalType('string', $this->model->rules('password'));
    }

    /**
     * @covers Fobia\Base\Model::getPrimaryKey
     * @todo   Implement testGetPrimaryKey().
     */
    public function testGetPrimaryKey()
    {
        $this->assertEquals('id', $this->model->getPrimaryKey());
    }

    /**
     * @covers Fobia\Base\Model::getClass
     * @todo   Implement testGetClass().
     */
    public function testGetClass()
    {
        $this->assertEquals(MyModel::CLASS_NAME, $this->model->getClass());
    }

    /**
     * @covers Fobia\Base\Model::getTableName
     * @todo   Implement testGetTableName().
     */
    public function testGetTableName()
    {
        $this->assertEquals('users', $this->model->getTableName());
    }

    public function testCreate1()
    {
        $db = \AppTest::instance()->db;
        $r  = $db->query("DELETE FROM users WHERE login = 'login5'");
        $this->assertTrue(($r) ? true : false);
    }

    /**
     * @depends testCreate1
     */
    public function testCreate2()
    {
        $model           = $this->model;
        $model->login    = 'login5';
        $model->password = 'password';

        $id = $model->create();
        $this->assertTrue(is_numeric($id));
        $this->assertFalse($model->create());
        return $id;
    }

    public function testNewModelNull()
    {
        $model = new MyModel();
        $this->assertEquals(null, $model->id);
        $this->assertFalse($model->select());
    }

    /**
     * @depends testCreate2
     */
    public function testNewModelInt($id)
    {
        $model = new MyModel((int) $id);
        $this->assertEquals($id, $model->id);
        $this->assertTrue($model->select());
    }

    /**
     * @depends testCreate2
     */
    public function testNewModelString($id)
    {
        $model = new MyModel((string) $id);
        $this->assertEquals(null, $model->id);
        $this->assertFalse($model->select());
    }

    /**
     * @depends testCreate2
     */
    public function testNewModelArray($id)
    {
        $model = new MyModel(array('id' => $id));
        $this->assertEquals($id, $model->id);
        $this->assertTrue($model->select());
    }

    /**
     * @depends testCreate2
     */
    public function testSelect($id)
    {
        $model     = $this->model;
        $model->id = $id;
        $model->select();
        $this->assertEquals('login5', $model->login);

        $model->select($id);
        $this->assertEquals('login5', $model->login);

        $model->select(array('id' => $id));

        $this->assertEquals('login5', $model->login);
        $this->assertEquals('password', $model->password);

        return $model;
    }

    /**
     * @depends testSelect
     */
    public function testUpdate($model)
    {
        $id              = $model->id;
        $model->password = 'password_update';
        $this->assertTrue($model->update());

        $this->model->id = $id;
        $this->model->select();
        $this->assertEquals('password_update', $this->model->password);
        return $model;
    }

    /**
     * @depends testUpdate
     */
    public function testDelete($model)
    {
        $id = $model->id;
        $this->assertTrue($model->delete());

        $model     = new MyModel();
        $model->id = $id;
        $this->assertFalse($model->select());
    }

    public function testToArray()
    {
        $arr = $this->model->toArray();
        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('login', $arr);
        $this->assertArrayHasKey('password', $arr);
    }
}