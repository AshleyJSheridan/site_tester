<?php
namespace Tester\Tests;

use Tester\Entities\HTMLIssue;

/**
 * Description of BasicHTMLAccessibilityTests
 *
 * @author Ashley Sheridan
 */
class BasicHTMLAccessibilityTests extends BaseTest
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
	
	public function test_image_alt_text()
	{
		$images = $this->parsed_content->getElementsByTagName('img');
		$images_have_alt_text = 0;
		
		foreach($images as $image)
		{
			if(!$image->hasAttribute('alt') )
			{
				$images_have_alt_text ++;
				
				if($this->issues_list->get_verbose() )
				{
					$image_html = $image->ownerDocument->saveHTML($image);

					$this->issues_list->add_issue(
						new HTMLIssue(
							"Image with missing alt text: $image_html",
							$this->content->get_url(),
							'maintainability',
							'warning'
						)
					);
				}
			}
		}
		
		if($images_have_alt_text > 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Images with missing alt text: $images_have_alt_text",
					$this->content->get_url(),
					'accessibility',
					'warning'
				)
			);
		}
	}
	
	public function test_unique_word_count()
	{
		$unique_threshold_percent = 30;
		
		$word_parser = new \Tester\Helpers\HTMLWordParser($this->parsed_content, 3, true, true);
		
		$total_words = $word_parser->get_word_count();
		$total_unique_words = $word_parser->get_unique_word_count();
		
		if(($total_unique_words / $total_words * 100) < $unique_threshold_percent)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Unique word count ratio below the threshold of $unique_threshold_percent",
					$this->content->get_url(),
					'interest',
					'warning'
				)
			);
		}
	}
	
	public function test_word_length_count()
	{
		// average word length sourced from https://arxiv.org/pdf/1208.6109.pdf
		$word_length_average = 5.1;
		$words_under_average = 0;
		$words_above_average = 0;
		
		$word_parser = new \Tester\Helpers\HTMLWordParser($this->parsed_content, 1, true, false);
		
		$word_length_counts = $word_parser->get_word_length_counts();
		
		foreach($word_length_counts as $word_length => $frequency)
		{
			if($word_length < $word_length_average)
				$words_under_average += $frequency;
			else
				$words_above_average += $frequency;
		}
		
		if($words_under_average < $words_above_average)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Majority of words are above the average word length of $word_length_average; content possibly too complicated",
					$this->content->get_url(),
					'accessibility',
					'warning'
				)
			);
		}
	}
	
	public function test_b_elements()
	{
		$b_elements = $this->parsed_content->getElementsByTagName('b');
		
		if($b_elements->length > 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Old-style bold <b> tags found; non-semantic",
					$this->content->get_url(),
					'accessibility'
				)
			);
			
			if($this->issues_list->get_verbose() )
			{
				foreach($b_elements as $bold_tag)
				{
					$bold_html = $bold_tag->ownerDocument->saveHTML($bold_tag);

					$this->issues_list->add_issue(
						new HTMLIssue(
							"Old-style bold <b> tag: $bold_html",
							$this->content->get_url(),
							'accessibility'
						)
					);
				}
			}
		}
	}
	
	public function test_i_elements()
	{
		$i_elements = $this->parsed_content->getElementsByTagName('i');
		
		if($i_elements->length > 0)
		{
			$this->issues_list->add_issue(
				new HTMLIssue(
					"Old-style italic <i> tags found; non-semantic",
					$this->content->get_url(),
					'accessibility'
				)
			);
			
			if($this->issues_list->get_verbose() )
			{
				foreach($i_elements as $italic_tag)
				{
					$italic_html = $italic_tag->ownerDocument->saveHTML($italic_tag);

					$this->issues_list->add_issue(
						new HTMLIssue(
							"Old-style italic <i> tag: $italic_html",
							$this->content->get_url(),
							'accessibility'
						)
					);
				}
			}
		}
	}
}
