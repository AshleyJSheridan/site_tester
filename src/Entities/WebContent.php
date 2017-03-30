<?php
namespace Tester\Entities;

/**
 * Description of WebContent
 *
 * @author Ashley Sheridan
 */
class WebContent
{
	private $headers;
	private $body;
	private $status_code;
	
	public function __construct($body, \Tester\ContentLists\HeaderList $headers, $status_code)
	{
		$this->body = $body;
		$this->headers = $headers;
		$this->status_code = $status_code;
	}
	
	public function __toString()
	{
		return $this->body;
	}
	
	public function get_headers()
	{
		return $this->headers;
	}
	
	public function get_header($header)
	{
		return $this->headers->get_header($header);
	}
	
	public function get_body()
	{
		return $this->body;
	}
	
	public function get_status_code()
	{
		return $this->status_code;
	}
}
