<?php
namespace Tester\Entities;

/**
 * Description of Issue
 *
 * @author ash
 */
class Issue
{
	private $message;
	private $type;
	
	public function __construct($message, $type = 'error')
	{
		$this->message = $message;
		$this->type = in_array($type, ['error', 'warning'])?$type:'error';
	}
}
