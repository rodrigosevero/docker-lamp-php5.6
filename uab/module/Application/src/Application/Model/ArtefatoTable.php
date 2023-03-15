<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ArtefatoTable
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

	public function getArtefatosAtivos()
	{
		$sql = 'select a.* from artefato a where a.del = 0 order by a.descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getArtefatosAtivosByArea($atividade_id)
	{
		$sql = 'select a.* from artefato a where a.del = 0 AND atividade_id = '.$atividade_id.' order by a.descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getArtefato($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveArtefato(Artefato $artefato)
	{		
		$data = array(
				'arquivo' => $artefato->arquivo,
				'atividade_id' => $artefato->atividade_id,
				'tipo' => $artefato->tipo,
				'descricao' => $artefato->descricao,
				'legenda' => $artefato->legenda,
				'data' => $artefato->data,
				'local' => $artefato->local,
				'del' => $artefato->del,
		);

		$id = (int) $artefato->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getArtefato($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('artefato id does not exist');
			}
		}
	}

	public function deleteArtefato($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}