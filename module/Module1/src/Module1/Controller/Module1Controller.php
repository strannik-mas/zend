<?php

namespace Module1\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class Module1Controller extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel();
    }
}