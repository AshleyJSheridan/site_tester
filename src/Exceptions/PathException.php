<?php
namespace Tester\Exceptions;

/**
 * Description of PathException
 *
 * @author Ashley Sheridan
 */
class PathException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
