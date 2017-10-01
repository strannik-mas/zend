<?php
namespace Page\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class PageService extends AbstractTableGateway{
//задаём имя таблицы
	protected $table = "page";
	
	//устанавливаем прототип результирующего множества
	public function __construct(Adapter $adapter){
		$this->adapter = $adapter;		//это свойство есть у AbstractTableGateway
		//установим прототип
		$this->resultSetPrototype = new ResultSet();
		//в кач. множества результатов установили прототип Page
		$this->resultSetPrototype-> setArrayObjectPrototype(new Page());
		
		$this->initialize();
	} 

	/*
	Title: ... 100 знаков
	Article: sdgln(100)
	
	*/
	
	
    public function getPage($id)  {
		$id = (int)$id;
//посмотреть как в запросе LENGTH(article, 100) сделать
		$rowSet = $this->select(array(
			"idpage"=>$id,
		));
		$row = $rowSet->current();
		//обработка неправильных вариантов
		if(!$row)
			throw new Exception("Не найдена страница $id");
		
		return $row;	
	} 

	
}


?>