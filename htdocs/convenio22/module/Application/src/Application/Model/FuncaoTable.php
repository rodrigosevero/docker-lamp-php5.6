<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class FuncaoTable
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

	public function getFuncoes()
	{
		$sql = 'select a.* from funcao a where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getFuncoes2()
	{
		$sql = 'select a.* from funcao a where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getNucleosAtivosporArea($area_id)
	{
		$sql = 'select * from nucleo where del = 0 and area_id = ' . $area_id;

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getFuncao($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row ".$id);
		}
		return $row;
	}

	

	public function saveFuncao(Funcao $funcao)
	{
		$data = array(
			'descricao' => $funcao->descricao,
			'codigo' => $funcao->codigo,
			'carga_horaria' => $funcao->carga_horaria,
			'meses' => $funcao->meses,
			'valor_inicial' => $funcao->valor_inicial,
			'valor_medio' => $funcao->valor_medio,
			'valor_final' => $funcao->valor_final,
			'quantidade' => $funcao->quantidade,			
			'requisitos' => $funcao->requisitos,			
		);

		$id = (int) $funcao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getFuncao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('funcao id does not exist');
			}
		}
	}

	public function deleteFuncao($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
