<?php

namespace Page;
//use Zend\Mvc\ModuleRouteListener;
//use Zend\Mvc\MvcEvent;
use Page\Model\Page;
use Page\Model\PageTable;
use Page\Model\PageService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


class Module
{
	
	public function init(){
		$myEventer = new \Page\Model\MyEventer();
		$myEventer->getEventManager()->attach("logmessage",  function($myevent)
		{
			$event = $myevent->getName();
			$target = get_class($myevent->getTarget());
			$params = json_encode($myevent->getParams());
			
			$logm = date("d-m-Y", time())."|".__METHOD__."|$target|event=$event|params=$params\r\n";
			
			file_put_contents("myeventer.log", $logm, FILE_APPEND);
		});
		$myEventer->logmessage("test_module");
	}
    public function getServiceConfig()
	{
		return array(
			"factories" => array(
			//как только ServiceManager запросит Page\Model\PageTable, то ...
				"Page\Model\PageTable" => function($serviceManager){
					$dbAdapter = $serviceManager->get("Zend\Db\Adapter\Adapter");
					$table = new PageTable($dbAdapter);
					return $table;
				},
					//чтоб возвращать адаптер с данными для подключения к бд из конфига
				'ZendDbAdapterAdapterdb1' => function($serviceManager){
                 $config = $serviceManager->get('Config');
                 return new \Zend\Db\Adapter\Adapter($config['db']);
				},
				"Page\Model\PageService" => function($serviceManager){
					$dbAdapter = $serviceManager->get("Zend\Db\Adapter\Adapter");
					$service = new PageService($dbAdapter);
					return $service;
				}
			),
		);
	}
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
