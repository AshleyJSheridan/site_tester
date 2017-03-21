<?php
namespace Tester\Entities;

/**
 * Description of Issue
 *
 * @author Ashley Sheridan
 */
class Issue
{
	private $message;
	private $url;
	private $type;
	private $category;
	
	public function __construct($message, $url, $category = null, $type = 'error')
	{
		$this->message = $message;
		$this->url = $url;
		$this->type = in_array($type, ['error', 'warning'])?$type:'error';
		
		if($category)
			$this->category = $category;
	}
	
	public function get_message()
	{
		return $this->message;
	}

	public function get_url()
	{
		return $this->url;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	public function get_category()
	{
		return $this->category;
	}
}
