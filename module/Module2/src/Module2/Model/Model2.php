<?php
namespace Module2\Model;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class Model2 implements EventManagerAwareInterface
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
	}
}