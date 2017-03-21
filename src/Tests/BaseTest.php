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
	
	public function __construct(\Tester\ContentLists\IssuesList &$issues_list)
	{
		$this->issues_list = $issues_list;
	}
	
	protected function get_test_methods()
	{
		$tests = preg_grep('/^test_/', get_class_methods($this) );
		
		return $tests;
	}
}
