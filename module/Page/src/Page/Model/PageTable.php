<?php
namespace Page\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class PageTable extends AbstractTableGateway{
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
	//получаем все страницы из базы
    public function fetchAll() {
		$resultSet = $this->select();
		return $resultSet;
	} 
	
	//получаем Paginator
    public function getPaginator($currentPage) {
		
		$adapter = new \Zend\Paginator\Adapter\DbSelect(new \Zend\Db\Sql\Select("page"), $this->adapter);
		
		$paginator = new \Zend\Paginator\Paginator($adapter);	
		$paginator->setCurrentPageNumber($currentPage);
		$paginator->setItemCountPerPage(4);
		
		return $paginator;
	} 
	
    public function getPage($id)  {
		$id = (int)$id;
		$rowSet = $this->select(array(
			"idpage"=>$id,
		));
		$row = $rowSet->current();
		//обработка неправильных вариантов
		if(!$row)
			throw new Exception("Не найдена страница $id");
		
		return $row;	
	} 
    public function savePage(Page $page)  {
		$data = array("title" => $page->title,
						"article" => $page->article,
						"pub" => $page->pub,
						
						//иначе не будет сохранятся дата в бд
						//"pub" => date("d-m-Y H:i:s"),
		);
		
		$id = (int) $page->idpage;
		if(!$id)
			$this->insert($data);
		else $this->update($data, array("idpage"=>$id,));
	} 
    public function deletePage($id){
		$id = (int)$id;
		$this->delete(array(
			"idpage"=>$id,
		));
	
	}
	
}


?>