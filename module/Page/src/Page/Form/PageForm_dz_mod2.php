<?php
namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Factory;
//дз
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class PageForm extends Form implements InputFilterAwareInterface{
	
	protected $inputFilter;
	
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
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		echo "не используется";
	}
	
	public function getInputFilter()
	{
		//эти фильтры относятся к модели формы PageForm
		if(!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory 	 = new InputFactory();
			
			$inputFilter->add($factory->createInput(array(
				"name" => "idpage",
				"required" => true,
				"filter" => array(
					array("name" => "Int"),
				),
			)));
			$inputFilter->add($factory->createInput(array(
				"name" => "article",
				"required" => true,
				"filter" => array(
					array("name" => "StripTags"),
					array("name" => "StringTrim"),
				),
				"validators" => array(
					array(
						"name" => "StringLength",
						"options" => array(
							"encoding" => "UTF-8",
							"min" => 5,
							"max" => 10000, //тут нужно смотреть какое ограничение в базе стоит на это поле
						),
					),
				),
			)));
		
			$inputFilter->add($factory->createInput(array(
				"name" => "title",
				"required" => true,
				"filter" => array(
					array("name" => "StripTags"),
					array("name" => "StringTrim"),
				),
				"validators" => array(
					array(
						"name" => "StringLength",
						"options" => array(
							"encoding" => "UTF-8",
							"min" => 5,
							"max" => 255, //тут нужно смотреть какое ограничение в базе стоит на это поле
						),
					),
				),
			)));
			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}
?>