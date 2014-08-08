<?php
namespace Fobia\Base;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-08-08 at 22:45:48.
 */
class UtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Fobia\Base\Utils::loadConfig
     * @todo   Implement testLoadConfig().
     */
    public function testLoadConfigIni()
    {
        $cfg = Utils::loadConfig(__DIR__ . '/files/config.ini');
        $this->assertArrayHasKey('cookies.lifetime', $cfg);
        $this->assertEquals('20 minutes', $cfg['cookies.lifetime']);
    }
    
    /**
     * @covers Fobia\Base\Utils::loadConfig
     * @todo   Implement testLoadConfig().
     */
    public function testLoadConfigPhp()
    {
        $cfg = Utils::loadConfig(__DIR__ . '/files/config.php');
        $this->assertArrayHasKey('cookies.lifetime', $cfg);
        $this->assertEquals('20 minutes', $cfg['cookies.lifetime']);
    }

    /**
     * @covers Fobia\Base\Utils::loadConfig
     * @todo   Implement testLoadConfig().
     */
    public function testLoadConfigYml()
    {
        $cfg = Utils::loadConfig(__DIR__ . '/files/config.yml');
        $this->assertArrayHasKey('cookies.lifetime', $cfg);
        $this->assertEquals('20 minutes', $cfg['cookies.lifetime']);
    }

    /**
     * @covers Fobia\Base\Utils::loadConfig
     * @todo   Implement testLoadConfig().
     */
    public function testLoadConfigFormat()
    {
        $cfg = Utils::loadConfig(__DIR__ . '/files/config_yml', 'yml');
        $this->assertArrayHasKey('cookies.lifetime', $cfg);
        $this->assertEquals('20 minutes', $cfg['cookies.lifetime']);
    }
    /**
     * @covers Fobia\Base\Utils::loadConfigCache
     * @todo   Implement testLoadConfigCache().
     */
    public function testLoadConfigCache()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::absolutePath
     * @todo   Implement testAbsolutePath().
     */
    public function testAbsolutePath()
    {
        $this->assertEquals('/foo/bar', Utils::absolutePath('/foo/bar', '/home/www'));
        $this->assertEquals('/home/www/foo/bar', Utils::absolutePath('foo/bar', '/home/www'));
        $this->assertEquals(SYSPATH, realpath(Utils::absolutePath('./', SYSPATH)));
    }

    /**
     * @covers Fobia\Base\Utils::letterTrans
     * @todo   Implement testLetterTrans().
     */
    public function testLetterTrans()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::getExecutionTime
     * @todo   Implement testGetExecutionTime().
     */
    public function testGetExecutionTime()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::getMemoryUsage
     * @todo   Implement testGetMemoryUsage().
     */
    public function testGetMemoryUsage()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::formatBytes
     * @todo   Implement testFormatBytes().
     */
    public function testFormatBytes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::resourceUsage
     * @todo   Implement testResourceUsage().
     */
    public function testResourceUsage()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::GetIp
     * @todo   Implement testGetIp().
     */
    public function testGetIp()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::GetPPID
     * @todo   Implement testGetPPID().
     */
    public function testGetPPID()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::location
     * @todo   Implement testLocation().
     */
    public function testLocation()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::parseURL
     * @todo   Implement testParseURL().
     */
    public function testParseURL()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::URLDecode
     * @todo   Implement testURLDecode().
     */
    public function testURLDecode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::parseTemplateFile
     * @todo   Implement testParseTemplateFile().
     */
    public function testParseTemplateFile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::parseTemplateString
     * @todo   Implement testParseTemplateString().
     */
    public function testParseTemplateString()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::randString
     * @todo   Implement testRandString().
     */
    public function testRandString()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::def
     * @todo   Implement testDef().
     */
    public function testDef()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::createClass
     * @todo   Implement testCreateClass().
     */
    public function testCreateClass()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Fobia\Base\Utils::isRequire
     * @todo   Implement testIsRequire().
     */
    public function testIsRequire()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
