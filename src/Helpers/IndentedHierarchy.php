<?php
namespace Tester\Helpers;

/**
 * Description of IndentedHierarchy
 *
 * @author Ashley Sheridan
 */
class IndentedHierarchy
{
	public static function get_indented_hierarchy($list)
	{
		$output = "\n";
		
		foreach($list as $item)
		{
			$output .= str_repeat(' ', (intval($item) - 1) * 2 );
			//output .= str_repeat(' ', (intval($item) - 1 * 2) );
			$output .= "$item\n";
		}
		
		return $output;
	}
}
