<?php
namespace Page\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Form\Annotation;

/**
 * @Annotation\Name("User")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class User{
/**
 * @Annotation\Exclude()
*/
/**
* @Annotation\Filter({"name":"StripTags"})
* @Annotation\Filter({"name":"StringTrim"})
* @Annotation\Validator({"name":"StringLength", "options":{"min":5}})
* @Annotation\Attributes({"type":"text"})
* @Annotation\Options({"label":"Login"})
*/

	public $login;
	
	/**
* @Annotation\Filter({"name":"StripTags"})
* @Annotation\Filter({"name":"StringTrim"})
* @Annotation\Validator({"name":"StringLength", "options":{"min":6}})
* @Annotation\Attributes({"type":"password"})
* @Annotation\Options({"label":"Password"})
*/

	public $password;
	
/**
* @Annotation\Attributes({"type":"submit", "value":"login"})
*/
	public $submit;
}
?>