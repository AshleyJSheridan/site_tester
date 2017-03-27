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
		$this->fetch_js_content_from_source();
	}

	public function fetch_css_content_from_source()
	{
		$this->css_content = \Tester\ContentLists\ResourceLister::get_css_links_from_content($this->get_content(), $this->get_url() );
	}

	public function fetch_js_content_from_source()
	{
		$this->js_content = \Tester\ContentLists\ResourceLister::get_js_links_from_content($this->get_content(), $this->get_url() );
	}

	public function get_css_links()
	{
		return $this->css_content;
	}
}
