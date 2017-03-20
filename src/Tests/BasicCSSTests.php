<?php
namespace Tester\Tests;

/**
 * Description of BasicCSSTests
 *
 * @author ash
 */
class BasicCSSTests extends BaseTest
{
	private $content;
	
	public function __construct(\Tester\ContentLists\IssuesList &$issues_list)
	{
		parent::__construct($issues_list);
	}
	
	public function run_tests(\Tester\WebContent\CSSWebContent $content)
	{
		$this->content = $content;
		
		$test_methods = $this->get_test_methods();
		
		foreach($test_methods as $test_method)
		{
			$this->{$test_method}($content);
		}
		
		//var_dump($this->issues_list);
		
		$this->content = null;
	}
	
	public function test_important()
	{
		$test = preg_match_all('/!\s?important/', $this->content, $matches);

		if($test)
		{
			$occurances = count($matches[0]);
			$this->issues_list->add_issue(new \Tester\Entities\CSSIssue("$occurances of !important found in {$this->content->get_url()}") );
		}
		else
			return true;
	}
	
	public function test_id_class_ratio()
	{
		/*
		 * #letters (anything except a space character according to html5 spec) which occurs after a 
		 * * newline
		 * * comma and space(s)
		 * * or another letter/number which would be a previous class/tag name/id
		 */
		$id_count = preg_match_all('/(?:(?:^|,)\s*|[\pL\pN])(#[^\s;]+)/ium', $this->content, $matches);
		
		
	}
}
