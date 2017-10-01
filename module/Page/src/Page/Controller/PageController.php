<?php
//отвечает за дополнение новых страниц

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Model\Page;
use Page\Model\MyServer;
use Page\Model\User;
use Page\Form\PageForm;
use Page\Form\PageContactForm;
use Application\Form\ContactForm;
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Model\MyAdapter;
use Zend\Session\Container;
use Page\Model\Acl;


class PageController extends AbstractActionController
{
	protected $pageTable;
	protected $acl;
	
	
	//http://zend/page
	//Page/Controller/Pagecontroller::indexAction
	public function indexAction()
	{
		$t = $this;
		$myEventer = new \Page\Model\MyEventer();
		/* $myEventer->getEventManager()->attach("logmessage", function($myevent) use ($t)
		{
			$event = $myevent->getName();
			$target = get_class($myevent->getTarget());
			$params = json_encode($myevent->getParams());
			$className = get_class($t);
			
			$logm = date("d-m-Y", time())."|".__METHOD__."|$target|event=$event|params=$params|CLASS=$className\r\n";
			
			file_put_contents("myeventer.log", $logm, FILE_APPEND);
		});  */
		$myEventer = new \Page\Model\MyEventer();
		$myEventer->logmessage("test_PageController");
		 
		 
		 
		$username = '';
		$password = '';
		$isVisiblePage = false;
		$request = $this->getRequest();
		
		$acl = new Acl();
		$this->acl = $acl->getAcl();

/*
 var_dump(get_class_methods($acl));
exit();  		
*/
		$container = new Container('logged');
		//$container->login = 'isLogin'; 
			
		//session_start();
		
			
		//if(!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] !== 1){
		if(!($container->offsetExists('login')) || $container->offsetGet('login') !== 1){
			if($request->isPost())
			{
				//фильтруем на лету
				$filter = new \Zend\Filter\StripTags();
				$validator = new \Zend\Validator\StringLength(array("min" => 1));
				$username = $filter->filter($request->getPost("login", "guest"));
				$password = $filter->filter($request->getPost("password", ""));
				
				if(!$validator->isValid($username) || !$validator->isValid($password))
					return $this->redirect()->toRoute("page");
				
				//читаем данные из конфига
				$serviceManager = $this->serviceLocator;
				$this->dbAdapter = $serviceManager->get('ZendDbAdapterAdapterdb1');
				
				$adapter = new MyAdapter($username, $password, $this->dbAdapter);
			
				$result = $adapter->authenticate();
				
				$isVisiblePage = $result->isValid() ? true : false;
				//$_SESSION["isLogin"] = $result->isValid() ? 1 : "";
				$l = $result->isValid() ? 1 : "";
				$container->offsetSet('login', $l);
				//$_SESSION["roleUser"] = $result->isValid() ? $username : "guest";
				$container->offsetSet('roleUser', $result->isValid() ? $username : "guest");
				
				//логгер
				$logger = new \Zend\Log\Logger();
				$writer = new \Zend\Log\Writer\Stream('log.txt');
				
/* $dbAdapter = new \Zend\Db\Adapter\Adapter(array(
			'driver'         => 'Pdo',
			'dsn'            => 'mysql:dbname=dbpage;host=localhost',
			'username' => 'root',
			'password' => '',
		));  */
				$columnMap  = array(
					'message'   => 'text',
				);
				$writerDB = new \Zend\Log\Writer\Db($this->dbAdapter, 'log', $columnMap);

				
				$logger->addWriter($writer);
				$logger->addWriter($writerDB);
				
				if($result->isValid())
					$logger->log(\Zend\Log\Logger::INFO, "$username Log in. ".date("d-m-Y H:i:s"));
				else 
					$logger->log(\Zend\Log\Logger::WARN, "$username not Log in. ".date("d-m-Y H:i:s"));

//var_dump($container->offsetGet('login'));
			}	
		}
		else{
			$isVisiblePage = true;
		}

/*
		$code = $result->getCode();
		$identity = $result->getIdentity();
		*/
			
		
		
		if($isVisiblePage)
		{
			$id = (int) $this->params()->fromRoute("id", 0);
			//$paginator = $this->getPageTable()->getPaginator($id);
			return array(
				"pages" => $this->getPageTable()->fetchAll(),	
				"acl"  => $this->acl,
				"paginator" => $this->getPageTable()->getPaginator($id),
			);
		}
			
		else {
			$builder = new AnnotationBuilder();
			$form    = $builder->createForm(new User());
			return array(
				"form" => $form,
			);	
		}
    }
	
	public function logoutAction(){
		session_start();
		session_regenerate_id();
		session_destroy();
		return $this->redirect()->toRoute("page");
	}
	public function contactAction()
	{
		session_start();
		//$form = new ContactForm();
		//тут надо всЄ проверить - по уроками как-то по-другому должно быть
		$builder = new AnnotationBuilder();
		$form    = $builder->createForm(new Page());
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
			$del = $request->getPost("del", "No"); //уточнить удал€ем или нет
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
		//св€зка формы со страницей
		$form->bind($page);
		$form->get("submit")->setAttribute("value", "Edit");
		$request = $this->getRequest();
		if($request->isPost())
		{
			$form->setInputFilter($page->getInputFilter());
			$form->setData($request->getPost());
		
			if($form->isValid())
			{
				
				//сохран€ем данные страницы через PageTable
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
			$form->setInputFilter($page->getInputFilter());			
			$form->setData($request->getPost());
			//используем установленный фильтр
			if($form->isValid())
			{
				//заполн€ем экземпл€р страницы данными из формы
				$page->exchangeArray($form->getData());
				//сохран€ем данные страницы через PageTable
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
	
	public function showAction()
	{
		$serviceManager = $this->serviceLocator;
		$service = $serviceManager->get('Page\Model\PageService');
		$id = (int) $this->params()->fromRoute("id", 0);
		$page = $service->getPage($id);
		
		return array(
			"page" => $page,
		);
	}
	
	public function serverAction()
	{
		/* $server = new \Zend\XmlRpc\Server;
		$server->setClass('Page\Model\MyServer', 'myserver');
		$server->handle();
		return new ViewModel(array("res" => $res)); 
		return new ViewModel();
		
			
		$server = new Zend\Json\Server\Server();
		// Indicate what functionality is available:
		$server->setClass('Calculator');
		// Handle the request:
		$server->handle();*/
		/*
		$server = new \Zend\Json\Server\Server();
		$server->setClass('Calculator');
		if ('GET' == $_SERVER['REQUEST_METHOD']) {
		// Indicate the URL endpoint, and the JSON-RPC version used:
		$server->setTarget('/json-rpc.php')
		->setEnvelope(Zend\Json\Server\Smd::ENV_JSONRPC_2);
		// Grab the SMD
		$smd = $server->getServiceMap();
		// Return the SMD to the client
		header('Content-Type: application/json');
		echo $smd;
		return;
		}
		$server->handle();
		*/
	}
	
	public function clientAction()
	{
		$client = new \Zend\XmlRpc\Client('http://zend/page/server');
		echo $client->call('myserver.sayHello');
	}
	
	public function randomAction()
	{
		$number = rand(0,100);
		//APC
		/* $apcAdapter = new \Zend\Cache\Storage\Adapter\Apc;
		$apcAdapter->getOptions()->setTtl(10);
		if($apcAdapter->hasItem("number")){
			$number = $apcAdapter->getItem("number");
			$number = $number[0];
		}
		else $apcAdapter->setItem("number", $number); */
		
		//echo "Value: ".$apcAdapter->getAvailableSpace();
			
		//FileCache
		/* $fileAdapter = new \Zend\Cache\Storage\Adapter\Filesystem;
		$fileAdapter->getOptions()->setTtl(10);
		$fileAdapter->getOptions()->setCacheDir("d:/domains/zend/data/cache/");
		if($fileAdapter->hasItem("number")){
			$number = $fileAdapter->getItem("number");
			$number = $number[0];
		}
		else $fileAdapter->setItem("number", $number); */
			
		//Memory Adapter
		$adapter = new \Zend\Cache\Storage\Adapter\Memory;
		$adapter->getOptions()->setTtl(10);
		echo $number.'<br>';
		
		if($adapter->hasItem("number")){
			$number = $adapter->getItem("number");
			//$number = $number[0];
		}
		else $adapter->setItem("number", $number);
		$number = rand(0, 1000);
		
		if($adapter->hasItem("number")){
			$number = $adapter->getItem("number");
			//$number = $number[0];
		}
		else $adapter->setItem("number", $number);
		//не мен€етс€,т.к. закешировалс€
		echo $number.'<br>';
		
		return new ViewModel(array(
			"number" => $number
		));
	}	
	
	public function getPageTable()
    {
		if(!$this->pageTable){
			//тут получаетс€ ошибка Deprecated: You are retrieving the service locator from within the class ...
			/*$serviceManager = $this->getServiceLocator();//получили доступ к менеджеру сервисов*/
			$serviceManager = $this->serviceLocator;//получили доступ к менеджеру сервисов
			//var_dump($serviceManager);
			$this->pageTable = $serviceManager->get('Page\Model\PageTable');
		} 
		return $this->pageTable;
    }
	
	public function mailAction(){
		
	}
	
	public function langAction(){
		
		$translator = new \Zend\I18n\Translator\Translator();
		//вручную
		$type = "phparray";
		$file = "./module/Page/src/Page/Controller/ru_RU.php";
		
		$translator->addTranslationFile($type, $file);
		echo $translator->translate("Home");
		
		/* $config = $this->getConfig();
		
		//ниче не работает
		$container = new Container('language');
		if(!($container->offsetExists('locale'))){
			$container->offsetSet('locale', $lang);
			$lang = (string) $this->params()->fromRoute("id", 0);
			switch($lang){
				case 1: setLocale(LC_ALL, "ru_RU"); break;
				case 2: setLocale(LC_ALL, "fr_FR"); break;
				default: setLocale(LC_ALL, "en_US");
			}			
		}
		return $this->redirect()->toRoute("page"); */
	}
	
}
