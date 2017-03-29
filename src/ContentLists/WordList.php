<?php
namespace Tester\ContentLists;

/**
 * Description of WordList
 *
 * @author Ashley Sheridan
 */
class WordList
{
	private $words = [];
	private $word_lengths = [];
	private $min_word_length;
	private $remove_punctuation;
	private $remove_stop_words;
	private $total = 0;
	
	public function __construct($min_word_length, $remove_punctuation = false, $remove_stop_words = true)
	{
		$this->min_word_length = intval($min_word_length);
		$this->remove_punctuation = $remove_punctuation;
		$this->remove_stop_words = $remove_stop_words;
	}
	
	public function add_increment_word_usage_total($word)
	{
		if($this->remove_stop_words && $this->get_is_stop_word($word) )
			return false;
		
		if($this->remove_punctuation)
			$word = $this->remove_punctuation($word);
		
		if(mb_strlen($word) >= $this->min_word_length)
		{
			if(!isset($this->words[$word]) )
				$this->words[$word] = 0;
			
			if(!isset($this->word_lengths[mb_strlen($word)]) )
				$this->word_lengths[mb_strlen($word)] = 0;

			$this->words[$word] ++;
			$this->word_lengths[mb_strlen($word)] ++;
			$this->total ++;
			
			return true;
		}
		else
			return false;
	}
	
	public function get_word_lengths()
	{
		$lengths = $this->word_lengths;
		ksort($lengths);
		
		return $lengths;
	}
	
	public function get_total_words_in_list()
	{
		return $this->total;
	}
	
	public function get_total_unique_words_in_list()
	{
		return count($this->words);
	}
	
	private function remove_punctuation($word)
	{
		return preg_replace('/\pP/u', '', $word);
	}
	
	private function get_is_stop_word($word)
	{
		// source from http://mkweb.bcgsc.ca/debates/stop.txt
		$stop_words = [
			"a","about","above","after","again","against","all","also","am","an","and","any","are","aren't","as","at",
			"be","because","been","before","being","below","between","both","but","by",
			"can't","cannot","could","couldn't","couldn't've",
			"did","didn't","didn't've","do","does","doesn't","doesn't've","doing","don't","down","during",
			"each",
			"few","for","from","further",
			"get","go","going","got",
			"had","hadn't","has","hasn't","have","haven't","having","he","he'd","he'll",
				"he's","her","here","here's","hers","herself","him","himself","his","how","how's",
			"i","i'd","i'll","i'm","i've","if","in","into","is","isn't","it","it's","its","itself",
			"let","let's",
			"me","more","most","mustn't","my","myself",
			"no","nor","not",
			"of","off","on","once","only","or","other","ought","our","ours","ourselves","out","over","own",
			"said","same","shan't","she","she'd","she'll","she's","should","shouldn't","so","some","such",
			"than","that","that's","the","their","theirs","them","themselves","then","there","there's",
				"these","they","they'd","they'll","they're","they've","this","those","through","to","too",
			"under","until","up","us",
			"very",
			"was","wasn't","we","we'd","we'll","we're","we've","were","we're","weren't","what","what's",
				"when","when's","where","where's","which","while","who","who's","who'll","who've",
				"whom","why","why's","with","won't","would","wouldn't",
			"you","you'd","you'll","you're","you've","your","yours","yourself","yourselves",
		];
		
		return in_array($word, $stop_words);
	}
}
