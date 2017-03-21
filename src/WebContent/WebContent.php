<?php
namespace Tester\WebContent;

/**
 * Description of WebContent
 *
 * @author Ashley Sheridan
 */
abstract class WebContent
{
	private $url;
	private $content = '';
	private $size = 0;

	public function __construct($url)
	{
		$this->url = $url;
		$this->content = $this->retrieve_content();
		$this->size = strlen($this->content);
	}

	public function __toString()
	{
		return $this->content;
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

	private function retrieve_content()
	{
		$ch = curl_init($this->url);
		$options = [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_MAXREDIRS => 10,
		];
		curl_setopt_array($ch, $options);

		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}
}
