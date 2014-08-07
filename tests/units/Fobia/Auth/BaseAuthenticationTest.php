<?php
namespace Fobia\Auth;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-06 at 01:05:04.
 */
class BaseAuthenticationTest extends \PHPUnit_Framework_TestCase
{

    protected $auth;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // 
    }

    protected function createAuth()
    {
        $auth = new \Fobia\Auth\BaseAuthentication(\AppTest::instance());
        return $auth;
    }

    /**
     * @covers Fobia\Auth\BaseAuthentication::getUser
     * @todo   Implement testGetUser().
     */
    public function testGetUser()
    {
        $auth = $this->createAuth();
        $this->assertInstanceOf('\\Fobia\\Auth\\BaseUserIdentity', $auth->getUser());
    }

    /**
     * @covers Fobia\Auth\BaseAuthentication::login
     * @todo   Implement testLogin().
     */
    public function testLogin()
    {
        $auth = $this->createAuth();
        $r = $auth->login('test@test', 'test');

        $this->assertTrue($r);
        $this->assertTrue($auth->hasIdentity());
    }

    /**
     * @covers Fobia\Auth\BaseAuthentication::logout
     * @todo   Implement testLogout().
     */
    public function testLogout()
    {
        $auth = $this->createAuth();
        
        $auth->login('test@test', 'test');
        $this->assertTrue($auth->hasIdentity());

        $auth->logout();
        $this->assertFalse($auth->hasIdentity());
    }

    /**
     * @covers Fobia\Auth\BaseAuthentication::hasIdentity
     * @todo   Implement testHasIdentity().
     */
    public function testHasIdentity()
    {
        $auth = $this->createAuth();
        $this->assertFalse($auth->hasIdentity());
        
        $auth->login('test@test', 'test');
        $this->assertTrue($auth->hasIdentity());
        
        $auth->logout();
        $this->assertFalse($auth->hasIdentity());
    }

    /**
     * @covers Fobia\Auth\BaseAuthentication::authenticate
     * @todo   Implement testAuthenticate().
     */
    public function testAuthenticate()
    {
        $auth_0 = $this->createAuth();
        $auth_0->login('test2@test', 'test');

        $app = \AppTest::instance();
        $auth = $this->createAuth();
        $auth->authenticate();

        // $auth->login('test2@test', 'test');
        // $this->assertTrue($auth->hasIdentity());
        $this->assertEquals('test2@test', $auth->getUser()->getUsername());
    }
}