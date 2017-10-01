<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\MyAdapter;
//use Application\Form\ContactForm;

class AuthController extends AbstractActionController
{
    public function indexAction()
    {
		$adapter = new MyAdapter('admin', 'qwerty');
		
		$result = $this->auth->authenticate($adapter);

		$code = $result->getCode();
		$identity = $result->getIdentity();
		
        return array(
			"code" => $code,
			"identity" => $identity,
		);
    }
	
	public function loginAction()
    {
        return new ViewModel();
    }
	public function logoutAction()
    {
        return new ViewModel();
    }
}
