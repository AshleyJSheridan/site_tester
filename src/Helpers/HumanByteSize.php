<?php
namespace Tester\Helpers;

/**
 * Description of HumanByteSize
 *
 * @author Ashley Sheridan
 */
class HumanByteSize
{
	public static function human_size($size, $system = 'bi', $retstring = '%01.0f %s', $max = null)
	{
		// Pick units
		$systems['si']['prefix'] = array('B', 'K', 'MB', 'GB', 'TB', 'PB');
		$systems['si']['size']   = 1000;
		$systems['bi']['prefix'] = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
		$systems['bi']['size']   = 1024;
		
		$sys = isset($systems[$system]) ? $systems[$system] : $systems['si'];
		
		// Max unit to display
		$depth = count($sys['prefix']) - 1;
		
		if ($max && false !== $d = array_search($max, $sys['prefix']))
			$depth = $d;
		
		$i = 0;
		
		while ($size >= $sys['size'] && $i < $depth)
		{
			$size /= $sys['size'];
			$i++;
		}
		return sprintf($retstring, $size, $sys['prefix'][$i]);
	}
}
