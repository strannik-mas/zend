<?php
namespace Page\Model;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class MyEventer implements EventManagerAwareInterface{
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
	
	
	public function logmessage($myparam){
		//$params = compact('baz', 'bat');
		
		$params = array($myparam);
		$this->getEventManager()->trigger(__FUNCTION__, $this, $params);

		file_put_contents("myeventer.log", "Отработка в logmessage: ".$params[0]."\r\n", FILE_APPEND);
	}
}	
?>