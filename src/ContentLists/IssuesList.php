<?php
namespace Tester\ContentLists;

/**
 * Description of IssuesList
 *
 * @author Ashley Sheridan
 */
class IssuesList extends \SplDoublyLinkedList
{
	public function add_issue(\Tester\Entities\Issue $issue)
	{
		parent::push($issue);
	}
}
