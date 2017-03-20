<?php

namespace Tester\ContentLists;

/**
 * Description of ResourceLister
 *
 * @author ash
 */
class ResourceLister
{
	public static function get_css_links_from_content($content, $base_url)
	{
		$css_list = new CSSList();

		preg_match_all('/<link.+"([^"]+\.css)"/', $content, $matches);

		if($matches)
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

			return $css_list;
		}
	}
}
