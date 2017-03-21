<?php

namespace Tester\Exceptions;

/**
 * Description of FetchResourceException
 *
 * @author Ashley Sheridan
 */
class FetchResourceException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
