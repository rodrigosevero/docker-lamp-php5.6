<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class BolsaTable
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

	public function getBolsas()
	{
		$sql = 'select a.* from bolsa a where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getBolsasGeral()
	{
		$sql = 'select a.*, c.descricao as area, (a.valor_inicial * meses) as valor_total, b.nome as colaborador,  b.data_admissao, b.data_inatividade
		from bolsa a 
		left join usuario b  on b.bolsa_id = a.id  
		left join area c on c.id = a.area_id
		where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getBolsa($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row ".$id);
		}
		return $row;
	}

	public function saveBolsa(Bolsa $bolsa)
	{
		$data = array(
			'descricao' => $bolsa->descricao,
			'codigo' => $bolsa->codigo,
			'carga_horaria' => $bolsa->carga_horaria,
			'meses' => $bolsa->meses,
			'valor_inicial' => $bolsa->valor_inicial,
			'valor_medio' => $bolsa->valor_medio,
			'valor_final' => $bolsa->valor_final,
			'quantidade' => $bolsa->quantidade,			
			'requisitos' => $bolsa->requisitos,			
		);

	
		$id = (int) $bolsa->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getBolsa($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('funcao id does not exist');
			}
		}
	}

	public function deleteBolsa($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
