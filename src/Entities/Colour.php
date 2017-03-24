<?php
namespace Tester\Entities;

/**
 * Description of Colour
 *
 * @author Ashley Sheridan
 */
class Colour
{
	private $colour;
	private $rule;
	
	public function __construct($colour, $rule)
	{
		$this->colour = $colour;
		$this->rule = $rule;
	}
	
	public function __toString()
	{
		return $this->colour;
	}
	
	public function get_colour()
	{
		return $this->colour;
	}
	
	public function get_rule()
	{
		return $this->rule;
	}
}
