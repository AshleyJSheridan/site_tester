<?php
namespace Tester\Exceptions;

/**
 * Description of CURLException
 *
 * @author Ashley Sheridan
 */
class CURLException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
