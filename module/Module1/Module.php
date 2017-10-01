<?php
namespace Module1;

use \Zend\Mvc\MvcEvent;
use \Zend\ModuleManager\ModuleManager;
use \Zend\ModuleManager\ModuleEvent as Event;


class Module
{
	
	public function init(ModuleManager $moduleManager)
    {
        // Remember to keep the init() method as lightweight as possible
        $events = $moduleManager->getEventManager();
        $events->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    public function modulesLoaded(Event $e)
    {
        // This method is called once all modules are loaded.
        $moduleManager = $e->getTarget();
        $loadedModules = $moduleManager->getLoadedModules();
        // To get the configuration from another module named 'FooModule'
        $module2 = $moduleManager->getModule('Module2');
		$config = $module2->getConfig();
		$str = $module2->some();
		file_put_contents("zend.log", $str."\r\n", FILE_APPEND);
    }
	
	public function onBootstrap(MvcEvent $e){
		/* $model1 = new \Module1\Model\Model1();
		$model2 = new \Module2\Model\Model2();
		
		$sharedEvents = new \Zend\EventManager\SharedEventManager();
		$sharedEvents->attach("*",'bar', function ($e){
			$event = $e->getName();
			$target = get_class($e->getTarget());
			$params = json_encode($e->getParams());
			$str = sprintf(
				'%s called on %s, using params %s',
				$event,
				$target,
				$params
			);
			file_put_contents("zend.log", $str."\r\n", FILE_APPEND);
		});
		
		$model1->getEventManager()->setSharedManager($sharedEvents);	
		$mySharedEvents = $model1->bar('baz1_2', 'bat1_2');
		
		$model2->getEventManager()->setSharedManager($mySharedEvents);	
		$model2->bar('baz2_2', 'bat2_2');  */
		
		
		
		/* $model1->getEventManager()->setSharedEventCollection($sharedEvents);
		$model2->getEventManager()->setSharedEventCollection($sharedEvents);
			
		$model1->bar('baz1', 'bat1');
		$model2->bar('baz2', 'bat2'); */
		/* $model1->getEventManager()->attach('bar', function ($e){
			$event = $e->getName();
			$target = get_class($e->getTarget());
			$params = json_encode($e->getParams());
			$str = sprintf(
				'%s called on %s, using params %s',
				$event,
				$target,
				$params
			);
			file_put_contents("zend.log", $str."\r\n", FILE_APPEND);
		}); 
			
		$model1->bar('baz1', 'bat1');
		$model2->bar('baz2', 'bat2');*/
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
