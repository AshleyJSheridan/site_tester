<?php
namespace Tester\WebContent;

/**
 * Description of WebPageContent
 *
 * @author Ashley Sheridan
 */
class WebPageContent extends WebContent
{
	private $css_content;

	public function __construct($url)
	{
		parent::__construct($url);
		
		$this->fetch_css_content_from_source();
	}

	public function fetch_css_content_from_source()
	{
		$this->css_content = \Tester\ContentLists\ResourceLister::get_css_links_from_content($this->get_content(), $this->get_url() );
	}

	public function get_css_links()
	{
		if(is_null($this->css_content) )
			throw new \Tester\Exceptions\FetchResourceException('CSS not fetched but CSS was requested');
		else
			return $this->css_content;
	}
}
