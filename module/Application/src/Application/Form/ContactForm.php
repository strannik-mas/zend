<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactForm extends Form{
	
	public function __construct($name = "contact")
	{
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		
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
		'type' => 'Zend\Form\Element\Checkbox',
		'name' => 'php',
		'options' => array(
			'label' => 'PHP',
			'use_hidden_element' => 'true',
			'checked_value' => 'yes',
			'unchecked_value' => 'no'
		),		
	));

	$this->add(array(
		'type' => 'Zend\Form\Element\Checkbox',
		'name' => 'js',
		'options' => array(
			'label' => 'JavaScript',
			'use_hidden_element' => 'true',
			'checked_value' => 'yes',
			'unchecked_value' => 'no'
		),		
	));

	$this->add(array(
		'type' => 'Zend\Form\Element\Radio',
		'name' => 'pol',
		'options' => array(
			'label' => 'Твой пол?',
			'value_options' => array(
				'0' => 'Мужской',
				'1' => 'Женский',
			),
		),		
	));

	$this->add(array(
		'type' => 'Zend\Form\Element\Select',
		'name' => 'language',
		'options' => array(
			'label' => 'Язык?',
			'empty_option' => 'Выбери язык',
			'value_options' => array(
				'0' => 'Русский',
				'1' => 'Английский',
				'2' => 'Японский',
				'3' => 'Китайский',
				'4' => 'Французский',
			),
		),		
	));

	$this->add(array(
		'type' => 'Zend\Form\Element\Color',
		'name' => 'color',
		'options' => array(
			'label' => 'Цвет?',
			'empty_option' => 'Выбери цвет',
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