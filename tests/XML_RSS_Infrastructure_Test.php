<?php
// +------------------------------------------------------------------------+
// | PEAR :: XML_RSS                                                        |
// +------------------------------------------------------------------------+
// | Copyright (c) 2004 Martin Jansen                                       |
// +------------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,        |
// | that is available at http://www.php.net/license/3_0.txt.               |
// | If you did not receive a copy of the PHP license and are unable to     |
// | obtain it through the world-wide-web, please send a note to            |
// | license@php.net so we can mail you a copy immediately.                 |
// +------------------------------------------------------------------------+
//
// $Id$
//

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'XML_RSS_Infrastructure_Test::main');
}

require_once "PHPUnit/Framework/TestCase.php";
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
        require_once 'PHPUnit/TextUI/TestRunner.php';
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
