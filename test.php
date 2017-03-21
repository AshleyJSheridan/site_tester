#!/usr/bin/env php
<?php
$loader = require __DIR__ . '/vendor/autoload.php';

$url = 'http://www.ashleysheridan.co.uk';

// get remote page and linked content
$page = new \Tester\WebContent\WebPageContent($url);
$page->fetch_css_content_from_source();
//$js_links = ResourceLister::get_js_links_from_content($page, $url);

// set up empty issues object that all tests can add to and specify whether verbose detailed issues should be allowed or not
$issues = new \Tester\ContentLists\IssuesList(false);

// set up CSS tests with the issues object that will contain any reported issues
$css_tests = new \Tester\Tests\BasicCSSTests($issues);


// run CSS tests
foreach($page->get_css_links() as $css_resource)
{
	$css_tests->run_tests($css_resource);
}

$issues_output = $issues->get_issues_as_string_list();

var_dump($issues_output);
