<?php
namespace Tester\Helpers;

/**
 * Description of HTMLWordParser
 *
 * @author Ashley Sheridan
 */
class HTMLWordParser
{
	private $dom_content;
	private $words;
	
	public function __construct(\DOMDocument $dom_content, $min_word_length = 3, $remove_punctuation = false, $remove_stop_words = true)
	{
		$this->dom_content = $dom_content;
		$this->words = new \Tester\ContentLists\WordCountList($min_word_length, $remove_punctuation, $remove_stop_words);
		
		$this->parse_content();
	}
	
	public function get_word_count()
	{
		return $this->words->get_total_words_in_list();
	}
	
	public function get_unique_word_count()
	{
		return $this->words->get_total_unique_words_in_list();
	}
	
	public function get_word_length_counts()
	{
		return $this->words->get_word_lengths();
	}
	
	public function get_words()
	{
		return $this->words->get_words();
	}
	
	private function parse_content()
	{
		$xp    = new \DOMXPath($this->dom_content);
		$nodes = $xp->query('/html/body//text()[
			not(ancestor::script) and
			not(ancestor::code) and
			not(normalize-space(.) = "")
		]');

		foreach($nodes as $node)
		{
			$sentence = $node->textContent;
			$words = preg_split('/[ \-\.,;:\?\!…\(\"\/\\\]/', $sentence);
			
			foreach($words as $word)
			{
				$word = trim(str_replace(array("\n", "\r"), ' ', $word) );
				$this->words->add_increment_word_usage_total($word);
			}
		}
	}
}
