<?php

namespace Fobia\Api\Method;

use Fobia\Api\Method\Method;

class MyMethod extends Method
{
    protected function execute()
    {

    }

}

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-03 at 22:58:33.
 */
class MethodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Method
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // $this->object = new Method;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }


    /**
     * @covers Fobia\Api\Method\Method::invoke
     * @todo   Implement testInvoke().
     */
    public function testInvoke()
    {
        $this->assertTrue(true);
    }

    
}