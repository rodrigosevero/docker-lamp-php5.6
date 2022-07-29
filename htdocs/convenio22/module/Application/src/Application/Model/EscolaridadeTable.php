<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class EscolaridadeTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getEscolaridades()
	{
		$sql = 'select a.* from escolaridade a order by a.descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
}