<?php

namespace Fobia\Api;

use Fobia\Api\Method as Method;

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
        \AppTest::instance();
    }


    /**
     * @covers Fobia\Api\Method::invoke
     * @todo   Implement testInvoke().
     */
    public function testInvoke()
    {
        $this->assertTrue(true);
    }


}