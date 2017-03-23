<?php
namespace Tester\Tests;

use Tester\Entities\CSSIssue;
use Tester\Helpers\HumanByteSize;

/**
 * Description of BasicCSSTests
 * tests based on:
 * * http://cssguidelin.es/
 * * https://csswizardry.com/2017/02/code-smells-in-css-revisited/
 *
 * @author Ashley Sheridan
 */
class BasicCSSTests extends BaseTest
{
	private $content;
	private $parsed_content;
	
	public function __construct(\Tester\ContentLists\IssuesList &$issues_list)
	{
		parent::__construct($issues_list);
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

	public function test_size()
	{
		$size_threshold = 50 * 1024;
		$size = strlen($this->content);
		
		if($size > $size_threshold)
		{
			$human_threshold = HumanByteSize::human_size($size_threshold);
			$human_size = HumanByteSize::human_size($size);

			$this->issues_list->add_issue(
				new CSSIssue(
					"CSS filesize of $human_size exceeds threshold of $human_threshold",
					$this->content->get_url(),
					'performance'
				)
			);
		}
	}

	public function test_important_count()
	{
		$test = preg_match_all('/!\s?important/', $this->content, $matches);

		if($test)
		{
			$occurances = count($matches[0]);
			$this->issues_list->add_issue(new CSSIssue("$occurances of !important found", $this->content->get_url(), 'maintainability' ) );
		}
	}
	
	public function test_extend_count()
	{
		$test = preg_match_all('/@extend/', $this->content, $matches);

		if($test)
		{
			$occurances = count($matches[0]);
			$this->issues_list->add_issue(new CSSIssue("$occurances of @extend found", $this->content->get_url(), 'performance' ) );
		}
	}
	
	public function test_import_count()
	{
		$test = preg_match_all('/@import/', $this->content, $matches);

		if($test)
		{
			$occurances = count($matches[0]);
			$this->issues_list->add_issue(new CSSIssue("$occurances of @import found", $this->content->get_url(), 'performance' ) );
		}
	}

	public function test_id_count()
	{
		$id_count = 0;
		$selectors = $this->parsed_content->getAllSelectors();
		
		foreach($selectors as $declaration_block)
		{
			$selector = $declaration_block->getSelector()[0]->getSelector();
			
			if(strstr($selector, '#') )
			{
				$id_count ++;
				
				if($this->issues_list->get_verbose() )
				{
					$this->issues_list->add_issue(
						new CSSIssue(
							"Styling by ID: $selector",
							$this->content->get_url(),
							'specificity'
						)
					);
				}
			}
		}
		
		if($id_count)
		{
			$this->issues_list->add_issue(
				new CSSIssue(
					"$id_count uses of styling by ID found",
					$this->content->get_url(),
					'specificity'
				)
			);
		}
	}
	
	public function test_selector_depth()
	{
		$depth_threshold = 3;
		$selectors_over_threshold = 0;
		$longest_depth = 0;
		$selectors = $this->parsed_content->getAllSelectors();
		
		foreach($selectors as $declaration_block)
		{
			$selector = $declaration_block->getSelector()[0]->getSelector();
			
			$tokens = explode(' ', $selector);
			
			if(count($tokens) > $longest_depth)
				$longest_depth = count($tokens);
			
			if(count($tokens) > $depth_threshold)
			{
				$selectors_over_threshold ++;
				
				if($this->issues_list->get_verbose() )
				{
					$this->issues_list->add_issue(
						new CSSIssue(
							"Selector threshold depth exceeded with $selector",
							$this->content->get_url(),
							'specificity'
						)
					);
				}
			}
		}
		
		if($longest_depth > $depth_threshold)
			$this->issues_list->add_issue(
				new CSSIssue(
					"Found $selectors_over_threshold selectors with depth greater than the threshold $depth_threshold. Longest was $longest_depth",
					$this->content->get_url(),
					'specificity'
				)
			);
	}
	
	public function test_background_shorthand()
	{
		$background_shorthand_count = 0;
		$rules = $this->parsed_content->getAllRuleSets();
		
		foreach($rules as $declaration_block)
		{
			$rule = $declaration_block->getRules()[0]->getRule();
			
			if($rule == 'background')
			{
				$background_shorthand_count ++;
				
				if($this->issues_list->get_verbose() )
				{
					$selector = $declaration_block->getRules()[0]->getSelector();
					
					$this->issues_list->add_issue(
						new CSSIssue(
							"background shorthand used in $selector",
							$this->content->get_url(),
							'maintainability',
							'warning'
						)
					);
				}
			}
		}
		
		if($background_shorthand_count)
			$this->issues_list->add_issue(
				new CSSIssue(
					"$background_shorthand_count occurances of 'background' shorthand rule found",
					$this->content->get_url(),
					'maintainability',
					'warning'
				)
			);
	}
	
	public function test_key_selector_frequency()
	{
		$frequency_threshold = 1;
		$selectors_over_threshold = 0;
		$selectors = $this->parsed_content->getAllSelectors();
		
		foreach($selectors as $declaration_block)
		{
			$selector = $declaration_block->getSelector()[0]->getSelector();
			
			if(strstr($selector, '*') )
			{
				$selectors_over_threshold ++;
				
				if($this->issues_list->get_verbose() )
				{
					$this->issues_list->add_issue(
						new CSSIssue(
							"Key selector * used in $selector",
							$this->content->get_url(),
							'performance'
						)
					);
				}
			}
		}
		
		if($selectors_over_threshold > $frequency_threshold)
			$this->issues_list->add_issue(
				new CSSIssue(
					"Found $selectors_over_threshold selectors using the * key selector, there should be no more than $frequency_threshold",
					$this->content->get_url(),
					'performance'
				)
			);
	}
	
	public function test_chained_classes()
	{
		$chained_classes_count = 0;
		$selectors = $this->parsed_content->getAllSelectors();
		
		foreach($selectors as $declaration_block)
		{
			$selector = $declaration_block->getSelector()[0]->getSelector();
			
			if(preg_match('/\.[^\. ]+\./', $selector) )
			{
				$chained_classes_count ++;
				
				if($this->issues_list->get_verbose() )
				{
					$this->issues_list->add_issue(
						new CSSIssue(
							"Chained class selectors in $selector",
							$this->content->get_url(),
							'specificity',
							'warning'
						)
					);
				}
			}
		}
		
		if($chained_classes_count)
		{
			$this->issues_list->add_issue(
				new CSSIssue(
					"Found $chained_classes_count chained class selectors",
					$this->content->get_url(),
					'specificity',
					'warning'
				)
			);
		}
	}
	
	public function test_duplicate_selectors()
	{
		$selector_list = [];
		$selector_duplicates = 0;
		$selectors = $this->parsed_content->getAllSelectors();
		
		foreach($selectors as $declaration_block)
		{
			$selector = $declaration_block->getSelector()[0]->getSelector();
			
			$selector_list[] = $selector;
		}
		
		$selectors_counts = array_count_values($selector_list);
		
		foreach($selectors_counts as $selector => $count)
		{
			if($count > 1)
			{
				$selector_duplicates += $count;
				
				if($this->issues_list->get_verbose() )
				{
					$this->issues_list->add_issue(
						new CSSIssue(
							"The selector '$selector' was found $count times",
							$this->content->get_url(),
							'maintainability',
							'warning'
						)
					);
				}
			}
		}
		
		if($selector_duplicates)
		{
			$this->issues_list->add_issue(
				new CSSIssue(
					"$selector_duplicates duplicate selectors found",
					$this->content->get_url(),
					'maintainability',
					'warning'
				)
			);
		}
	}
	
	public function test_colours_used()
	{
		$colours = [];
		// unfortunately the parser can't be used for this test as it doesn't appear to capture rules like box-shadow, etc
		/*$rules = $this->parsed_content->getAllRuleSets();
		
		foreach($rules as $declaration_block)
		{
			$rule = $declaration_block->getRules()[0]->getRule();
			
			if(preg_match('/^(background(-color)?$|color|(box|text)-shadow|(border|outline)(top|left|bottom|right)?(-color)?)/', $rule) )
				echo $rule;

		}*/

		// this is as neat as I could make a regex to find properties that can have colours
		// even ignoring vendor-specific prefixes, I'm sure I've missed some...
		$rule_regex = '/\b(background(?:-color)?|color|(?:box|text)-shadow|(?:border|outline)(?:top|left|bottom|right)?(?:-color)?)\s*:\s*([^;]+?);/m';
		
		// ugly regex which should match against the majority of colours, with the obvious exception of named colours
		$colour_regex = '/((?:rgba?|hsl)\([^\)]+?\))|(\#(?:[0-9a-f]{6}|[0-9a-f]{3}))/';
		
		preg_match_all($rule_regex, $this->content, $matches);
		
		if(count($matches) )
		{
			foreach($matches[2] as $rule_value)
			{
				preg_match_all($colour_regex, $rule_value, $colour_matches);
				
				if(count($colour_matches) )
					$colours[] = $colour_matches[0];
				//var_dump($colour_matches);
			}
		}
		
		var_dump($colours);
	}
}
