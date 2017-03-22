<?php
namespace Tester\Entities;

/**
 * Description of Header
 *
 * @author Ashley Sheridan
 */
class Header
{
	private $header;
	private $value;
	
	public function __construct($header, $value)
	{
		$this->header = $header;
		$this->value = $value;
	}
	
	public function get_header()
	{
		return $this->header;
	}
	
	public function get_value()
	{
		return $this->value;
	}
	
	public function __toString()
	{
		return "$this->header: $this->value";
	}
}
