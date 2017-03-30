<?php
namespace Tester\Tests;

use Tester\Entities\HTMLIssue;
use Tester\Helpers\HumanByteSize;
use Tester\Helpers\IndentedHierarchy;

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
		$this->parsed_content = new \DOMDocument();
		
		libxml_use_internal_errors(true);
		$this->parsed_content->loadHTML($this->content);
		libxml_use_internal_errors(false);
		
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
	
	public function test_doctype()
	{
		$nodes = $this->parsed_content->childNodes;
		
		if(!$nodes[0] instanceof \DOMDocumentType)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"HTML should always contain a doctype as the first element",
					$this->content->get_url(),
					'validity'
				)
			);
		}
	}
	
	public function test_title()
	{
		$titles = $this->parsed_content->getElementsByTagName('title');
		
		if($titles->length == 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should have a title element",
					$this->content->get_url(),
					'validity'
				)
			);
		}
		
		if($titles->length > 1)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should only have a single title element",
					$this->content->get_url(),
					'validity'
				)
			);
		}
		
		if(empty($titles[0]->nodeValue) )
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Page title should not be empty",
					$this->content->get_url(),
					'validity'
				)
			);
		}
	}
	
	public function test_header()
	{
		$headers = $this->parsed_content->getElementsByTagName('header');
		
		if($headers->length == 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should have a defined <header>",
					$this->content->get_url(),
					'structure',
					'warning'
				)
			);
		}
	}
	
	public function test_footer()
	{
		$footers = $this->parsed_content->getElementsByTagName('footer');
		
		if($footers->length == 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should have a defined <footer>",
					$this->content->get_url(),
					'structure',
					'warning'
				)
			);
		}
	}
	
	public function test_navigation()
	{
		$nav_elements = $this->parsed_content->getElementsByTagName('nav');
		$nav_roles = preg_match('/role\s*=\s*[\'"]?navigation/', $this->content->get_content() );
		
		if($nav_elements->length == 0 && !$nav_roles)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should have defined navigation",
					$this->content->get_url(),
					'structure',
					'warning'
				)
			);
		}
	}
	
	public function test_headings()
	{
		preg_match_all('/<h([1-6])[^>]*>/', $this->content->get_content(), $headings);

		if(empty($headings[1]) )
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Pages should have defined headings",
					$this->content->get_url(),
					'structure',
					'warning'
				)
			);
		}
		else
		{
			if($headings[1][0] != '1')
			{
				$this->issues_list->add_issue(
					new HTMLIssue(
						"First heading in a page should be '<h1>', '<h{$headings[1][0]}>' found",
						$this->content->get_url(),
						'structure',
						'warning'
					)
				);
			}
			
			$last_heading_level = 0;
			foreach($headings[1] as $heading_level)
			{
				$heading_level = intval($heading_level);

				if($heading_level > ($last_heading_level + 1) )
				{
					$heading_level_structure = IndentedHierarchy::get_indented_hierarchy($headings[1]);
					
					$this->issues_list->add_issue(
						new HTMLIssue(
							"Headings should follow a logical order, found $heading_level_structure",
							$this->content->get_url(),
							'structure',
							'warning'
						)
					);
					
					break;
				}
				
				$last_heading_level = $heading_level;
			}
		}
	}
	
	public function test_css_links()
	{
		$css_link_count_threshold = 2;
		$css_links = $this->content->get_css_links();
		$total_css_links = count($css_links);
		
		if($total_css_links > $css_link_count_threshold)
		{
			$issue_message = "The total number of linked CSS files exceeds the threshold of $css_link_count_threshold; found $total_css_links";
			
			if($this->issues_list->get_verbose() )
			{
				foreach($css_links as $css_link)
					$issue_message .= "\n* " . $css_link->get_url();
			}
			
			$this->issues_list->add_issue(
				new HTMLIssue(
					$issue_message,
					$this->content->get_url(),
					'performance',
					'warning'
				)
			);
		}
	}
	
	public function test_js_links()
	{
		$js_link_count_threshold = 3;
		$js_links = $this->content->get_js_links();
		$total_js_links = count($js_links);
		
		if($total_js_links > $js_link_count_threshold)
		{
			$issue_message = "The total number of linked JS files exceeds the threshold of $js_link_count_threshold; found $total_js_links";
			
			if($this->issues_list->get_verbose() )
			{
				foreach($js_links as $js_link)
					$issue_message .= "\n* " . $js_link->get_url();
			}
			
			$this->issues_list->add_issue(
				new HTMLIssue(
					$issue_message,
					$this->content->get_url(),
					'performance',
					'warning'
				)
			);
		}
	}
	
	public function test_inline_scripts()
	{
		$total_js_links = count($this->content->get_js_links() );

		$all_scripts = $this->parsed_content->getElementsByTagName('script');

		if($all_scripts->length > $total_js_links)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Inline <script> tags found",
					$this->content->get_url(),
					'maintainability',
					'warning'
				)
			);
		}
	}
	
	public function test_inline_style_blocks()
	{
		$style_blocks = $this->parsed_content->getElementsByTagName('style');
		
		if($style_blocks->length > 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Inline <style> blocks found",
					$this->content->get_url(),
					'maintainability',
					'warning'
				)
			);
		}
	}
	
	public function test_inline_styles()
	{
		$xpath = new \DOMXpath($this->parsed_content);
		
		$elements = $xpath->query("//*[@style]");
		
		if($elements->length > 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Inline styles found on tags",
					$this->content->get_url(),
					'maintainability',
					'warning'
				)
			);
		}
	}
	
	public function test_page_age()
	{
		$recent_threshold_days = 30;
		$recent_threshold = 60 * 60 * 24 * $recent_threshold_days;
		$last_modified = strtotime($this->content->get_header('Last-Modified') );

		// attempt to fetch a date from meta tags if the Last-Modified header is not set
		if(!$last_modified)
		{
			$meta_tags = $this->parsed_content->getElementsByTagName('meta');
			
			foreach($meta_tags as $meta)
			{
				$meta_name = $meta->hasAttribute('name')?$meta->getAttribute('name'):null;
				
				if($meta_name && preg_match('/dc\.(created|modified)/i', $meta_name) && $meta->hasAttribute('content') )
					$last_modified = strtotime($meta->getAttribute('content') );
			}
		}
		
		if(!$last_modified || (time() - $last_modified) < $recent_threshold)
		{
			$apparent_age = date("Y-m-d H:i:s", $last_modified);
			
			$this->issues_list->add_issue(
				new HTMLIssue(
					"The apparent age of this document ($apparent_age) appears to be older than the threshold of $recent_threshold_days days",
					$this->content->get_url(),
					'interest',
					'warning'
				)
			);
		}
	}
}
