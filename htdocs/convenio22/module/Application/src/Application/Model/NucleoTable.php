<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class NucleoTable
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

	public function getNucleosAtivos()
	{
		$sql = 'select a.* from nucleo a where a.del = 0 ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getNucleosAtivosporArea($area_id)
	{
		$sql = 'select * from nucleo where del = 0 and area_id = '.$area_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getNucleo($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveNucleo(Nucleo $nucleo)
	{
		$data = array(
				'area_id' => $nucleo->area_id,
				'descricao' => $nucleo->descricao,
				'coordenador_id' => $nucleo->coordenador_id,
				'del' => $nucleo->del,
		);

		$id = (int) $nucleo->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getnucleo($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('nucleo id does not exist');
			}
		}
	}

	public function deleteNucleo($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}