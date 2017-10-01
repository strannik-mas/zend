<?php
//отвечает за дополнение новых страниц

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Model\Page;
use Page\Form\PageForm;
use Page\Form\PageContactForm;
use Zend\Form\Annotation\AnnotationBuilder;


class PageController extends AbstractActionController
{
	protected $pageTable;
	//http://zend/page
	//Page/Controller/Pagecontroller::indexAction
    public function indexAction()
    {
		//var_dump($this->getPageTable());
        return new ViewModel(
			
			array(
				"pages" => $this->getPageTable()->fetchAll(),
			));
		
    }
	public function contactAction()
	{
		//$form = new PageContactForm();
		$builder = new AnnotationBuilder();
		$form    = $builder->createForm('Page\Model\Page');
		return array("form" => $form);
	}
	
	//http://zend/page/delete
	//Page/Controller/Pagecontroller::deleteAction
	public function deleteAction()
    {
		$id = (int) $this->params()->fromRoute("id", 0);
		if(!$id)
		{
			return $this->redirect()->toRoute("page");
		}
		$request = $this->getRequest();
		if($request->isPost())
		{
			$del = $request->getPost("del", "No"); //уточнить удаляем или нет
			if($del == "Yes")
			{
				$this->getPageTable()->deletePage($id);
			}
			return $this->redirect()->toRoute("page");
		}		
        return array(
			'id' => $id,
			'page' => $this->getPageTable()->getPage($id),
		);
    }
	//http://zend/page/modify
	//Page/Controller/Pagecontroller::modifyAction
	public function modifyAction()
    {
		$id = (int) $this->params()->fromRoute("id", 0);
		if(!$id)
		{
			return $this->redirect()->toRoute("page", array("actiton" => "add"));
		}
		$page = $this->getPageTable()->getPage($id);
		$form = new PageForm();
		$form->bind($page);
		$form->get("submit")->setAttribute("value", "Edit");
		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($page->getInputFilter());
			$form->setData($request->getPost());
		
			if($form->isValid())
			{
				
				//сохраняем данные страницы через PageTable
				$this->getPageTable()->savePage($page);
				
				return $this->redirect()->toRoute("page");
			}
		}
        return array(
			'id' => $id,
			'form' => $form
		);
    }
	//http://zend/page/add
	//Page/Controller/Pagecontroller::addAction
	public function addAction()
    {
		$form = new PageForm();
		$request = $this->getRequest();
		if($request->isPost())
		{
			$page = new Page();
			//$form->setInputFilter($page->getInputFilter());
			//ДЗ!!
			$form->getInputFilter();
			$form->setData($request->getPost());
			//используем установленный фильтр
			if($form->isValid())
			{
				//заполняем экземпляр страницы данными из формы
				$page->exchangeArray($form->getData());
				//сохраняем данные страницы через PageTable
				$this->getPageTable()->savePage($page);
				
				return $this->redirect()->toRoute("page");
			}
		}
		
        return new ViewModel(array("form" => $form));
		
    }
	
	//http://zend/page/sitemap
	//Page/Controller/Pagecontroller::sitemapAction
	public function sitemapAction()
	{
		return new ViewModel();
	}
	
	public function getPageTable()
    {
		if(!$this->pageTable){
			//тут получается ошибка Deprecated: You are retrieving the service locator from within the class ...
			/*$serviceManager = $this->getServiceLocator();//получили доступ к менеджеру сервисов*/
			$serviceManager = $this->serviceLocator;//получили доступ к менеджеру сервисов
			//var_dump($serviceManager);
			$this->pageTable = $serviceManager->get('Page\Model\PageTable');
			//var_dump($this->pageTable);
		} 
		return $this->pageTable;
    }
}
