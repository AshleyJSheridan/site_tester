<?php
namespace Tester\ContentLists;

/**
 * Description of IssuesList
 *
 * @author Ashley Sheridan
 */
class IssuesList extends \SplDoublyLinkedList
{
	private $verbose;
	
	public function __construct($verbose = false)
	{
		$this->verbose = $verbose;
	}
	
	public function add_issue(\Tester\Entities\Issue $issue)
	{
		parent::push($issue);
	}
	
	public function get_verbose()
	{
		return $this->verbose;
	}
}
