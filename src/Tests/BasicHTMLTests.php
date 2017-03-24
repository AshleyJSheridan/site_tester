<?php
namespace Tester\Tests;

use Tester\Entities\HTMLIssue;
use Tester\Helpers\HumanByteSize;

/**
 * Description of BasicHTMLTests
 *
 * @author Ashley Sheridan
 */
class BasicHTMLTests extends BaseTest
{
	public function run_tests(\Tester\WebContent\WebPageContent $content)
	{
		$this->content = $content;
		
		$test_methods = $this->get_test_methods();
		
		foreach($test_methods as $test_method)
		{
			$this->{$test_method}($content);
		}
	}
	
	public function test_size()
	{
		$size_threshold = 50 * 1024;
		$size = strlen($this->content);
		
		if($size > $size_threshold)
		{
			$human_threshold = HumanByteSize::human_size($size_threshold);
			$human_size = HumanByteSize::human_size($size);

			$this->issues_list->add_issue(
				new HTMLIssue(
					"HTML filesize of $human_size exceeds threshold of $human_threshold",
					$this->content->get_url(),
					'performance'
				)
			);
		}
	}
}
