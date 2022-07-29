<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AtividadePTCProjetoTable
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

	public function getAtividadesPTCProjetoAtivasByAtividade($atividade_projeto_id)
	{
		$sql = 'select * from atividade_ptc_atividade_projeto where del = 0 and atividade_projeto = '.$atividade_projeto_id.' GROUP BY atividade_ptc_id';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadePTCProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveAtividadePTC(AtividadePTCProjeto $atividade_ptc_projeto)
	{		
		$data = array(
				
				'area_id' => $atividade_ptc_projeto->area_id,
				'atividade_projeto' => $atividade_ptc_projeto->atividade_projeto,
				'atividade_ptc_id' => $atividade_ptc_projeto->atividade_ptc_id,
				'del' => $atividade_ptc_projeto->del,
		);

		$id = (int) $atividade_ptc_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getAtividadePTCProjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Atividade PTC id does not exist');
			}
		}
	}

	public function deleteAtividadesPTCProjetoByArea($id)
	{
		$this->tableGateway->delete(array('area_id' => (int) $id));
	}

	public function deleteAtividadePTCProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}