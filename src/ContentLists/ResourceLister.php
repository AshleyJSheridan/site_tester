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
				$full_css_link = self::get_full_url_path($css_link, $base_url);
				$css_list->push(new \Tester\WebContent\CSSWebContent($full_css_link) );
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
	
	public static function get_full_url_path($url, $base_url)
	{
		if(preg_match('/^https?\:\/\//', $url) )
			return $url;
		
		if(preg_match('/^\/\/[^\/]/', $url) )
		{
			$protocol = parse_url($base_url, PHP_URL_SCHEME);
			
			return "$protocol:$url";
		}
		
		if(preg_match('/^\/[^\/]/', $url) )
		{
			$protocol = parse_url($base_url, PHP_URL_SCHEME);
			$domain = parse_url($base_url, PHP_URL_HOST);
			$port = parse_url($base_url, PHP_URL_PORT);
			$user = parse_url($base_url, PHP_URL_USER);
			$pass = parse_url($base_url, PHP_URL_PASS);
			
			$full_url = $protocol . '://';
			
			if($user)
			{
				$full_url .= $user;
				
				if($pass)
					$full_url .= ":$pass";
				
				$full_url .= '@';
			}
			
			$full_url .= $domain;
			
			if($port)
				$full_url .= ":$port";
			
			$full_url .= $url;
			
			return $full_url;
		}
	}
}
