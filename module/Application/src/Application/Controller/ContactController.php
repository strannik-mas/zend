<?php
//контроллер для не работает - работает contactAction в IndexController

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ContactForm;

use Zend\Mail;


class ContactController extends AbstractActionController
{
    public function indexAction()
    {
		
		
		$request = $this->getRequest();
		if($request->isPost())
		{
			$filter = new \Zend\Filter\StripTags();
			
			$email = $filter->filter($request->getPost("title", ""));
			$body = $filter->filter($request->getPost("article", ""));
			
echo $email, " ", $body;
exit();
			$mail = new Mail\Message();
			$mail->setBody($body)->setSubject("от меня")->setFrom("privat_m@ukr.net", "sadmin")->addTo($email, "Ув клиент!");
			
			$transport = new Mail\Transport\Sendmail();
			$transport->send($mail);
			
			$this->flashMessenger()->addMessage('Твоя почта отправлена');

			return $this->redirect()->toRoute("page");  	//index.phtml Application\contact - page
		}		
        
		$flashMessenger = $this->flashMessenger();
		if ($flashMessenger->hasMessages()) {
			$messages = $flashMessenger->getMessages();
		}
		return array($messages);
    }
}
