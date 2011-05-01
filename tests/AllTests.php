<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'XML_RSS_Infrastructure_Test.php';
require_once 'XML_RSS_Parsing_Test.php';

class AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('XML_RSS Tests');
		$suite->addTestSuite('XML_RSS_Parsing_Test');
		$suite->addTestSuite('XML_RSS_Infrastructure_Test');

		return $suite;
	}
}

if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
	AllTests::main();
}
// vim:set noet ts=4 sw=4:
