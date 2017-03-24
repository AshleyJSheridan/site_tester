<?php
namespace Tester\Tests;

/**
 * Description of BaseTest
 *
 * @author Ashley Sheridan
 */
abstract class BaseTest
{
	protected $issues_list;
	private $tests = [];
	protected $content;
	protected $parsed_content;
	
	public function __construct(\Tester\ContentLists\IssuesList &$issues_list)
	{
		$this->issues_list = $issues_list;
	}

	protected function get_test_methods()
	{
		$tests = preg_grep('/^test_/', get_class_methods($this) );
		
		return $tests;
	}
	
	public function run_tests(\Tester\WebContent\CSSWebContent $content)
	{
		$this->content = $content;
		$parser = new \Sabberworm\CSS\Parser($content);
		$this->parsed_content = $parser->parse();
		
		$test_methods = $this->get_test_methods();
		
		foreach($test_methods as $test_method)
		{
			$this->{$test_method}($content);
		}

		$this->content = null;
		$this->parsed_content = null;
	}
}
