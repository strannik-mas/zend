<?php
namespace Page\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Annotation;

/**
 * @Annotation\Name("Page\Model\Page")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class Page implements InputFilterAwareInterface{
/**
 * @Annotation\Exclude()
*/
/**
* @Annotation\Filter({"name":"Int"})
* @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[0-9]+$/"}})
* @Annotation\Attributes({"type":"hidden"})
*/
//создаём свойства - поля в таблице из бд
	public $idpage;
	
/**
* @Annotation\Filter({"name":"StripTags"})
* @Annotation\Filter({"name":"StringTrim"})
* @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":100}})
* @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
* @Annotation\Attributes({"type":"text"})
* @Annotation\Options({"label":"Title:"})
*/
	public $title;
/**
* @Annotation\Filter({"name":"StripTags"})
* @Annotation\Filter({"name":"StringTrim"})
* @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":100}})
* @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
* @Annotation\Attributes({"type":"textarea"})
* @Annotation\Options({"label":"Статья:"})
*/
	public $article;
/**
* @Annotation\Attributes({"type":"text"})
* @Annotation\Options({"label":"DATE:"})
*/
	public $pub;

	
	protected $inputFilter;
	
	public function exchangeArray($data){
		$this->idpage = (isset($data["idpage"]))? $data["idpage"] : 0;
		$this->title = (isset($data["title"]))? $data["title"] : null;
		$this->article = (isset($data["article"]))? $data["article"] : null;
		$this->pub = (isset($data["pub"]))? $data["pub"] : null;
	}
	    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new Exception ("не используется");
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