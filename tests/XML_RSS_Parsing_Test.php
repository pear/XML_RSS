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

require_once "PHPUnit.php";
require_once "PHPUnit/TestCase.php";
require_once "PHPUnit/TestSuite.php";
require_once "XML/RSS.php";

/**
 * Unit test suite for the XML_RSS package
 *
 * @author  Martin Jansen <mj@php.net>
 * @extends PHPUnit_TestCase
 * @version $Id$
 */
class XML_RSS_Parsing_Test extends PHPUnit_TestCase {

    function testParseLocalFile() {
        $result = array("PHP homepage" => "http://php.net/",
                        "PEAR homepage" => "http://pear.php.net/",
                        "PHP-GTK homepage" => "http://gtk.php.net/",
                        "PHP QA homepage" => "http://qa.php.net/");
        $values = array_values($result);
        $keys = array_keys($result);
        $i = 0;

        $r =& new XML_RSS("test.rss");
        $r->parse();

        $this->assertEquals(count($r->getItems()), 4);

        foreach ($r->getItems() as $value) {
            $this->assertEquals($value['title'], $keys[$i]);
            $this->assertEquals($value['link'], $values[$i]);
            $i++;
        }
    }
}

$suite = new PHPUnit_TestSuite("XML_RSS_Parsing_Test");
$result = PHPUnit::run($suite);
echo $result->toString();
