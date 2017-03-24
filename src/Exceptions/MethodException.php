<?php
namespace Tester\Exceptions;

/**
 * Description of MethodException
 *
 * @author Ashley Sheridan
 */
class MethodException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
