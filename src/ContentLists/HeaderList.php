<?php
namespace Tester\ContentLists;

/**
 * Description of HeaderList
 *
 * @author Ashley Sheridan
 */
class HeaderList extends \SplDoublyLinkedList
{
	public function add_header(\Tester\Entities\Header $header)
	{
		parent::push($header);
	}
	
	public function get_headers_as_string()
	{
		$header_string = '';
		
		$this->rewind();
		
		while($this->valid() )
		{
			$current_header =  $this->current();
			
			$header_string .= "$current_header\r\n";
			
			$this->next();
		}
		
		return $header_string;
	}
	
	public function get_header($header)
	{
		$this->rewind();
		$header_value = null;

		while($this->valid() )
		{
			$current_header =  $this->current();
			
			if($current_header->get_header() == $header)
			{
				$header_value = $current_header->get_value();
				break;
			}
			
			$this->next();
		}
		
		return $header_value;
	}
}
