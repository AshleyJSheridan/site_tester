#!/usr/bin/env php
<?php
$loader = require __DIR__ . '/vendor/autoload.php';

$url = 'http://www.ashleysheridan.co.uk/blog/Manual+Vs+Automated+Testing';

// get remote page and linked content
$page = new \Tester\WebContent\WebPageContent($url);

// set up empty issues object that all tests can add to and specify whether verbose detailed issues should be allowed or not
$issues = new \Tester\ContentLists\IssuesList(true);

$test_class_searcher = new \Tester\Helpers\TestClassSearcher();
//$test_class_searcher->run_css_tests($page->get_css_links(), $issues);
$test_class_searcher->run_html_tests($page, $issues);


$issues_output = $issues->get_issues_as_string_list();

echo $issues_output;
