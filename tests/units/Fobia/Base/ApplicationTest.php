<?php
namespace Fobia\Base;

use Fobia\Base\Application;
use App;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-03 at 22:43:54.
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->app = App::create();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Fobia\Base\Application::getInstance
     * @todo   Implement testGetInstance().
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf('\\Fobia\\Base\\Application', Application::getInstance());
    }

    /**
     * @covers Fobia\Base\Application::setInstance
     * @todo   Implement testSetInstance().
     */
    public function testSetInstance()
    {
        $app = new Application();// App::create();
        $this->assertNotEquals($app, Application::getInstance());

        Application::setInstance($app);
        $this->assertInstanceOf('\\Fobia\\Base\\Application', Application::getInstance());
        $this->assertEquals($app, Application::getInstance());
    }

}