<?php
namespace Tester\ContentLists;

/**
 * Description of ColourList
 *
 * @author Ashley Sheridan
 */
class ColourList extends \SplDoublyLinkedList
{
	public function push($value)
	{}
	
	public function add_colour(\Tester\Entities\Colour $colour)
	{
		parent::push($colour);
	}
	
	public function get_counts()
	{
		$colours = [];
		
		$this->rewind();
		
		while($this->valid() )
		{
			$current_colour =  $this->current();
			
			$colour = $current_colour->get_colour();
			
			if(!array_key_exists($colour, $colours) )
				$colours[$colour] = 0;
			
			$colours[$colour] ++;
			
			$this->next();
		}
		
		return $colours;
	}
	
	public function get_total()
	{
		$colours = $this->get_counts();
		
		return count($colours);
	}
	
	public function get_colour_count_as_string()
	{
		$count_string = '';
		$colours = $this->get_counts();
		arsort($colours);
		
		foreach($colours as $colour => $total)
		{
			$count_string .= "$colour = $total; ";
		}
		
		return $count_string;
	}
}
