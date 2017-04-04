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

	private function get_test_methods()
	{
		$tests = preg_grep('/^test_/', get_class_methods($this) );
		
		return $tests;
	}
	
	protected function run_test_methods(\Tester\WebContent\WebContent $content)
	{
		$test_methods = $this->get_test_methods();
		
		foreach($test_methods as $test_method)
		{
			$this->{$test_method}($content);
		}
	}
	
	protected function html_setup(\Tester\WebContent\WebContent $content)
	{
		$this->content = $content;
		$this->parsed_content = new \DOMDocument();
		$this->dictionary = \pspell_new('en', '', '', '', PSPELL_FAST);
		
		libxml_use_internal_errors(true);
		$this->parsed_content->loadHTML($this->content);
		libxml_use_internal_errors(false);
	}
	
	protected function css_setup(\Tester\WebContent\WebContent $content)
	{
		$this->content = $content;
		$parser = new \Sabberworm\CSS\Parser($content);
		$this->parsed_content = $parser->parse();
	}
	
	protected function css_cleanup()
	{
		$this->content = null;
		$this->parsed_content = null;
	}
}
