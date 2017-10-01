<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ContactForm;
use Zend\Mail;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
		$menu = new \Application\Model\MyNavigation;
        return new ViewModel(array(
			"menu" => $menu
		));
		
    }
	
	public function contactAction()
	{
		$request = $this->getRequest();
		if($request->isPost())
		{
			$filter = new \Zend\Filter\StripTags();
			
			$email = $filter->filter($request->getPost("title", ""));
			$body = $filter->filter($request->getPost("article", ""));
			
//echo $email, " ", $body;
//exit();
			$mail = new Mail\Message();
			$mail->setBody($body);
			$mail->setSubject("от меня");
			$mail->setFrom("privat_m@ukr.net", "sadmin");
			$mail->addTo($email);
//var_dump($mail->headers);
			$transport = new Mail\Transport\Sendmail();
			$transport->send($mail);
			
			$this->flashMessenger()->addMessage('Твоя почта отправлена');

			return $this->redirect()->toRoute("contact");  	//index.phtml Application\contact - page
		}		
        
		$flashMessenger = $this->flashMessenger();
		if ($flashMessenger->hasMessages()) {
			$messages = $flashMessenger->getMessages();
		}
		
    
		
		
		$form = new ContactForm();
		return new ViewModel(array(
			"form" => $form,
			"messages" => $messages,
		));
	}
}
