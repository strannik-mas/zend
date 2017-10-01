<?php

namespace Module2\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class Module2Controller extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel();
    }
}