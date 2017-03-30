<?php
namespace Tester\WebContent;

use Tester\ContentLists\HeaderList;
/**
 * Description of WebContent
 *
 * @author Ashley Sheridan
 */
abstract class WebContent
{
	private $url;
	private $content;
	private $status_code;
	private $header_size = 0;
	private $size = 0;

	public function __construct($url)
	{
		$this->url = $url;
		$this->retrieve_and_set_content();
		$this->size = strlen($this->content);
	}

	public function __toString()
	{
		return $this->content->get_body();
	}

	public function get_size()
	{
		return $this->size;
	}

	public function get_url()
	{
		return $this->url;
	}

	public function get_content()
	{
		return $this->content;
	}
	
	public function get_header($header)
	{
		return $this->content->get_header($header);
	}

	private function retrieve_and_set_content()
	{
		$ch = curl_init($this->url);
		$options = [
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_MAXREDIRS => 10,
		];
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);
	
		if($content === false)
			throw new \Tester\Exceptions\CURLException("Remote resource $this->url could not be retrieved");
		
		$this->header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headers = $this->fetch_headers($content);

		curl_close($ch);

		$this->content = new \Tester\Entities\WebContent($this->get_body($content), $headers, $status_code);
		
	}
	
	private function get_body($content)
	{
		return substr($content, $this->header_size);
	}
	
	private function fetch_headers($content)
	{
		$headers_list = new HeaderList();
		$raw_headers = substr($content, 0, $this->header_size);

		preg_match_all('/^([^: ]+): ([^\n]+$)/m', $raw_headers, $headers);
		
		if(!empty($headers[1]) )
		{
			for($i=0; $i<count($headers[1]); $i++)
			{
				$header_key = trim($headers[1][$i]);
				$header_value = trim($headers[2][$i]);
				
				$headers_list->add_header(new \Tester\Entities\Header($header_key, $header_value) );
			}
		}
		
		return $headers_list;
	}
}
