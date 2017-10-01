<?php
namespace Application\Model;

class MyNavigation
{
	public $navigation;
	
	public function __construct()
	{
		
		//всё это в module.config Application
		$pages = array(
			array(
				'label' => 'Home',
				'route' => 'home',
				'id'	=> 'home',
			),
			array(
				'label' => 'Pages',
				'route' => 'page',
				'pages' => array(array(
						'label' => 'Add page',
						'route' => 'page',
						'module' => 'page',
						'controller' => 'index',
						'action' => 'add',
					),					
				),
			),
			array(
				'label' => 'Contact',
				'route' => 'contact',
			),
		); 
		$this->navigation = new \Zend\Navigation\Navigation($pages);
	}
}
?>