<?php

namespace Tester\ContentLists;

/**
 * Description of ResourceLister
 *
 * @author Ashley Sheridan
 */
class ResourceLister
{
	public static function get_css_links_from_content($content, $base_url)
	{
		$css_list = new CSSList();

		preg_match_all('/<link.+"([^"]+\.css)"/', $content, $matches);

		if(!empty($matches[1]) )
		{
			foreach($matches[1] as $css_link)
			{
				if(preg_match('/^https?\:\/\//', $css_link) )
				{
					$css_list->push(new \Tester\WebContent\CSSWebContent($css_link) );
				}
				else
				{
					$divider = substr($css_link, 0, 1) == '/'?'':'/';
					$css_list->push(new \Tester\WebContent\CSSWebContent($base_url . $divider . $css_link) );
				}
			}
		}
		
		return $css_list;
	}
	
	public static function get_js_links_from_content($content, $base_url)
	{
		$js_list = new JSList();
		
		preg_match_all('/<script.+"([^"]+\.js)"/', $content, $matches);
		
		if(!empty($matches[1]) )
		{
			foreach($matches[1] as $js_link)
			{
				if(preg_match('/^https?\:\/\//', $js_link) )
				{
					$js_list->push(new \Tester\WebContent\JSWebContent($css_link) );
				}
				else
				{
					$divider = substr($js_link, 0, 1) == '/'?'':'/';
					$js_list->push(new \Tester\WebContent\JSWebContent($base_url . $divider . $js_link) );
				}
			}
		}
		
		return $js_list;
	}
}
