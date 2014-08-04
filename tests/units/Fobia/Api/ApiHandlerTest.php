<?php
namespace Fobia\Api;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-03 at 22:59:46.
 */
class ApiHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApiHandler
     */
    protected $api;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->api = new ApiHandler();
        $map = include __DIR__ . '/map.php';
        $this->api->addMap($map);
    }


    /**
     * @covers Fobia\Api\ApiHandler::request
     * @todo   Implement testRequest().
     */
    public function testRequest()
    {
        $result = $this->api->request('test.one');

        // var_dump($result);
    }

    /**
     * @covers Fobia\Api\ApiHandler::getClass
     * @todo   Implement testGetClass().
     */
    public function testGetClass()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
