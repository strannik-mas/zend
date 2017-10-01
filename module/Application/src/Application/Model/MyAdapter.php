<?php
namespace Application\Model;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Adapter\Adapter;

class MyAdapter extends AuthAdapter implements AdapterInterface
{
	public $username;
	public $password;
	public function __construct($username, $password, $dbAdapter)
	{

		//лучше из конфига, но...
/* 		$dbAdapter = new Adapter(array(
			'driver'         => 'Pdo',
			'dsn'            => 'mysql:dbname=dbpage;host=localhost',
			'username' => 'root',
			'password' => '',
		)); */

		parent::__construct($dbAdapter , "user", "user", "password");
		$this->username = $username;
		$this->password = $password;
		$this->setIdentity($username)->setCredential($password);
	}
	public function authenticate()
	{
		$res = parent::authenticate();
		return $res;
		
		$this->setIdentity($this->username)->setCredential($this->password);
		$flag = ($this->username == "admin" && $this->password == "qwerty") ? true : false;		//флаг ошибки
		
		
		if($flag)
			return new Result(Result::SUCCESS, "user");
		else 
			return new Result(Result::FAILURE, "guest"); 
	}
}
?>