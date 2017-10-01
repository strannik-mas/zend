<?php
namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Factory;

class PageForm extends Form{
	
	public function __construct($name = "page")
	{
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		//$this->setAttribute('target', '__blank');
		$this->setAttribute('enctype', 'application/x-www-form-urlencoded');
		$this->setAttribute('id', 'pageform');
		
		$this->add(array(
			"name" => "idpage",
			"attributes" => array(
				"type" => "hidden",
				"value" => "0",
			),
		));
		$this->add(array(
			"name" => "article",
			"attributes" => array(
				"type" => "textarea",
			),
			'options' => array(
				"label" => "Описание",
			),
		));
	
		$title = new Element("title");
		$title->setAttribute("type", 'text');
		$title->setLabel("Заголовок");
		
		$this->add($title);
		
		$this->add(array(
			"name" => "pub",
			"attributes" => array(
				"type" => "text",
				//"type" => "date",
			),
			'options' => array(
				"label" => "Дата",
			),
		));
	
		$this->add(array(
			"name" => "submit",
			"attributes" => array(
				"type" => "submit",
				"value" => "Поехали",
				"id" => "submitbutton",
			),
		));
	
	}
}
?>