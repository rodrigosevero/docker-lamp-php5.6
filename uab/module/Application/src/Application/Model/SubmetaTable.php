<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class SubmetaTable
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

	public function getSubmetasAtivasporArea($area_id)
	{
		$sql = 'select * from submeta where del = 0 and area_id = '.$area_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getSubmeta($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveSubmeta(Submeta $submeta)
	{
		$data = array(
				'area_id' => $submeta->area_id,
				'descricao' => $submeta->descricao,
				'codigo' => $submeta->codigo,
				'del' => $submeta->del,
		);

		$id = (int) $submeta->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getsubmeta($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('submeta id does not exist');
			}
		}
	}

	public function deleteSubmeta($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}