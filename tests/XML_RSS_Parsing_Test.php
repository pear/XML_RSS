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
    define('PHPUnit_MAIN_METHOD', 'XML_RSS_Parsing_Test::main');
}

require_once "PHPUnit/Framework.php";
require_once "XML/RSS.php";

/**
 * Unit test suite for the XML_RSS package
 *
 * @author  Martin Jansen <mj@php.net>
 * @extends PHPUnit_TestCase
 * @version $Id$
 */
class XML_RSS_Parsing_Test extends PHPUnit_Framework_TestCase
{
    public static function main()
    {
        require_once 'PHPUnit/TextUI/TestRunner.php';
        PHPUnit_TextUI_TestRunner::run(
            new PHPUnit_Framework_TestSuite('XML_RSS_Parsing_Test')
        );
    }


    function testParseLocalFile() {
        $result = array("PHP homepage" => "http://php.net/",
                        "PEAR homepage" => "http://pear.php.net/",
                        "PHP-GTK homepage" => "http://gtk.php.net/",
                        "PHP QA homepage" => "http://qa.php.net/");
        $values = array_values($result);
        $keys = array_keys($result);
        $i = 0;

        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $this->assertEquals(count($r->getItems()), 4);

        foreach ($r->getItems() as $value) {
            $this->assertEquals($value['title'], $keys[$i]);
            $this->assertEquals($value['link'], $values[$i]);
            $i++;
        }
    }

    function testGetStructure()
    {
        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $expected = 'a:7:{i:0;a:4:{s:4:"type";s:7:"channel";s:5:"title";s:4:"Test";s:4:"link";s:20:"http://pear.php.net/";s:11:"description";s:34:"This is a test channel for XML_RSS";}i:1;a:4:{s:4:"type";s:5:"image";s:5:"title";s:4:"PEAR";s:3:"url";s:38:"http://pear.php.net/gifs/pearsmall.gif";s:4:"link";s:20:"http://pear.php.net/";}i:2;a:3:{s:4:"type";s:4:"item";s:5:"title";s:12:"PHP homepage";s:4:"link";s:15:"http://php.net/";}i:3;a:3:{s:4:"type";s:4:"item";s:5:"title";s:13:"PEAR homepage";s:4:"link";s:20:"http://pear.php.net/";}i:4;a:3:{s:4:"type";s:4:"item";s:5:"title";s:16:"PHP-GTK homepage";s:4:"link";s:19:"http://gtk.php.net/";}i:5;a:3:{s:4:"type";s:4:"item";s:5:"title";s:15:"PHP QA homepage";s:4:"link";s:18:"http://qa.php.net/";}i:6;a:5:{s:4:"type";s:9:"textinput";s:5:"title";s:15:"Search Slashdot";s:11:"description";s:23:"Search Slashdot stories";s:4:"name";s:5:"query";s:4:"link";s:29:"http://slashdot.org/search.pl";}}';
        $actual = serialize($r->getStructure());

        $this->assertEquals($expected, $actual);
    }

    function testGetStructureFromString()
    {
        $rss = file_get_contents(dirname(__FILE__) . '/test.rss');
        $r =& new XML_RSS($rss);
        $r->parse();

        $expected = 'a:7:{i:0;a:4:{s:4:"type";s:7:"channel";s:5:"title";s:4:"Test";s:4:"link";s:20:"http://pear.php.net/";s:11:"description";s:34:"This is a test channel for XML_RSS";}i:1;a:4:{s:4:"type";s:5:"image";s:5:"title";s:4:"PEAR";s:3:"url";s:38:"http://pear.php.net/gifs/pearsmall.gif";s:4:"link";s:20:"http://pear.php.net/";}i:2;a:3:{s:4:"type";s:4:"item";s:5:"title";s:12:"PHP homepage";s:4:"link";s:15:"http://php.net/";}i:3;a:3:{s:4:"type";s:4:"item";s:5:"title";s:13:"PEAR homepage";s:4:"link";s:20:"http://pear.php.net/";}i:4;a:3:{s:4:"type";s:4:"item";s:5:"title";s:16:"PHP-GTK homepage";s:4:"link";s:19:"http://gtk.php.net/";}i:5;a:3:{s:4:"type";s:4:"item";s:5:"title";s:15:"PHP QA homepage";s:4:"link";s:18:"http://qa.php.net/";}i:6;a:5:{s:4:"type";s:9:"textinput";s:5:"title";s:15:"Search Slashdot";s:11:"description";s:23:"Search Slashdot stories";s:4:"name";s:5:"query";s:4:"link";s:29:"http://slashdot.org/search.pl";}}';
        $actual = serialize($r->getStructure());

        $this->assertEquals($expected, $actual);
    }

    function testGetChannelInfo()
    {
        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $expected = array(
            'title'         => 'Test',
            'link'          => 'http://pear.php.net/',
            'description'   => 'This is a test channel for XML_RSS'
        );

        $actual = $r->getChannelInfo();

        $this->assertEquals($expected, $actual);
    }

    function testGetItems()
    {
        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $expected = array();
        $expected[] = array('title' => 'PHP homepage', 'link' => 'http://php.net/');
        $expected[] = array('title' => 'PEAR homepage', 'link' => 'http://pear.php.net/');
        $expected[] = array('title' => 'PHP-GTK homepage', 'link' => 'http://gtk.php.net/');
        $expected[] = array('title' => 'PHP QA homepage', 'link' => 'http://qa.php.net/');

        $actual = $r->getItems();

        $this->assertEquals($expected, $actual);
    }

    function testGetImages()
    {
        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $expected = array();
        $expected[] = array(
            'title' => 'PEAR',
            'url'   => 'http://pear.php.net/gifs/pearsmall.gif',
            'link'  => 'http://pear.php.net/'
        );

        $actual = $r->getImages();

        $this->assertEquals($expected, $actual);
    }

    function testGetTextinputs()
    {
        $r =& new XML_RSS(dirname(__FILE__) . '/test.rss');
        $r->parse();

        $expected = array(
            'title'         => 'Search Slashdot',
            'description'   => 'Search Slashdot stories',
            'name'          => 'query',
            'link'          => 'http://slashdot.org/search.pl'
        );

        $actual = $r->getTextinputs();

        $this->assertEquals($expected, $actual);
    }

}


if (PHPUnit_MAIN_METHOD == 'XML_RSS_Parsing_Test::main') {
    XML_RSS_Parsing_Test::main();
}
?>