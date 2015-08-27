<?php
/**
 * Unit testing for XML_RSS infrastructure
 *
 * PHP Version 5
 *
 * @category XML
 * @package  XML_RSS
 * @author   Martin Jansen <mj@php.net>
 * @license  PHP License http://php.net/license
 * @version  Release: @PACKAGE_VERSION@
 * @link     XML_RSS_Infrastructure_Test.php
 */

$version = '@package_version@';
if (strstr($version, 'package_version')) {
    set_include_path(dirname(dirname(__FILE__)) . ':' . get_include_path());
}
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'XML_RSS_Infrastructure_Test::main');
}

if (stream_resolve_include_path('PHPUnit/Framework/TestCase.php')) {
    include_once 'PHPUnit/Framework/TestCase.php';
}
require_once "XML/RSS.php";

/**
 * Unit test suite for the XML_RSS package
 *
 * This test suite does not provide tests that make sure that XML_RSS
 * parses XML files correctly. It only ensures that the "infrastructure"
 * works fine.
 *
 * @author  Martin Jansen <mj@php.net>
 * @extends PHPUnit_TestCase
 * @version $Id$
 */
class XML_RSS_Infrastructure_Test extends PHPUnit_Framework_TestCase
{
    public static function main()
    {
        if (stream_resolve_include_path('PHPUnit/TextUI/TestRunner.php')) {
            include_once 'PHPUnit/TextUI/TestRunner.php';
        }
        PHPUnit_TextUI_TestRunner::run(
            new PHPUnit_Framework_TestSuite('XML_RSS_Infrastructure_Test')
        );
    }

    /**
     * Test case for making sure that XML_RSS extends from XML_Parser
     */
    function testIsXML_Parser() {
        $rss = new XML_RSS();
        $this->assertTrue(is_a($rss, "XML_Parser"));
    }

    /**
     * Test case for bug report #2310
     *
     * @link http://pear.php.net/bugs/2310/
     */
    function testBug2310() {
        $rss = new XML_RSS("", null, "utf-8");
        $this->assertEquals($rss->tgtenc, "utf-8");

        $rss = new XML_RSS("", "utf-8", "iso-8859-1");
        $this->assertEquals($rss->srcenc, "utf-8");
        $this->assertEquals($rss->tgtenc, "iso-8859-1");
    }
}

if (PHPUnit_MAIN_METHOD == 'XML_RSS_Infrastructure_Test::main') {
    XML_RSS_Infrastructure_Test::main();
}
?>
