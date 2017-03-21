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
	
	public function get_issues_as_string_list()
	{
		$issues_list = "\n";
		
		$this->rewind();
		
		while($this->valid() )
		{
			$current_issue =  $this->current();
			
			$issues_list .= "{$current_issue->get_type()}: {$current_issue->get_category()}\n";
			$issues_list .= "{$current_issue->get_url()}: {$current_issue->get_message()}\n\n";
			
			$this->next();
		}
		
		return $issues_list;
	}
}
