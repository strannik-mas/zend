<?php
namespace Module1\Model;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class Model1 implements EventManagerAwareInterface
{
	protected $events;
	public function setEventManager(EventManagerInterface $events)
	{
		$events->setIdentifiers(array(
		__CLASS__,
		get_called_class(),
		));
		$this->events = $events;
		return $this;
	}
	public function getEventManager()
	{
		if (null === $this->events) {
		$this->setEventManager(new EventManager());
		}
		return $this->events;
	}
	
	public function bar($baz, $bat = null)
	{
		$params = compact('baz', 'bat');
		$this->getEventManager()->trigger(__FUNCTION__, $this, $params);
		
		$sharedEvents = new \Zend\EventManager\SharedEventManager();
		$sharedEvents->attach("*",'bar', function ($e){
			$event = $e->getName();
			$target = get_class($e->getTarget());
			$params = json_encode($e->getParams());
			$str = sprintf(
				__CLASS__.': MYLOG %s called on %s, using params %s',
				$event,
				$target,
				$params
			);
			file_put_contents("zend.log", $str."\r\n", FILE_APPEND);
		});
		
		return $sharedEvents;
	}
}