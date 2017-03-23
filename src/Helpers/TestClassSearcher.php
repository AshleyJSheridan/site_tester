<?php
namespace Tester\Helpers;

/**
 * Description of TestClassSearcher
 *
 * @author Ashley Sheridan
 */
class TestClassSearcher
{
	private $test_dir;
	private $test_class_files;
	
	public function __construct()
	{
		$this->test_dir = realpath(__DIR__ . '/../Tests');
		
		if($this->test_dir === false)
			throw new \Tester\Exceptions\PathException("The test directory $this->test_dir does not exist");
		
		$this->test_class_files = new \Tester\ContentLists\TestClassList();
		
		$this->search_and_add_test_classes();
	}
	
	public function get_test_classes()
	{
		return $this->test_class_files;
	}
	
	public function run_css_tests($files_to_test, $issues_list)
	{
		foreach($this->get_test_classes() as $test_class)
		{
			if(!stristr($test_class->getFilename(), 'css') )
				continue;
			
			$extension = $test_class->getExtension();
			$class_name = $test_class->getBasename(".$extension");

			$namespaced_class = "\\Tester\\Tests\\$class_name";
			$tester = new $namespaced_class($issues_list);
			
			foreach($files_to_test as $file_to_test)
			{
				$tester->run_tests($file_to_test);
			}
		}
	}
	
	private function search_and_add_test_classes()
	{
		$dir = new \RecursiveDirectoryIterator($this->test_dir);
		$iterator = new \RecursiveIteratorIterator($dir);
		
		$files_iterator = new \RegexIterator($iterator, '/(\.php)/');
		
		foreach($files_iterator as $file)
		{
			if($file->getFilename() === 'BaseTest.php')
				continue;
			
			$this->test_class_files->push($file);
		}
	}
}
