<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class TipoProjetoTable
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

	public function getTipoProjetosAtivos()
	{
		$sql = 'select * from tipo_projeto where del = 0 order by descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getTipoProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveTipoProjeto(TipoProjeto $tipo_projeto)
	{
		$data = array(
				'descricao' => $tipo_projeto->descricao,
				'del' => $tipo_projeto->del,
		);

		$id = (int) $tipo_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->gettipo_projeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('tipo_projeto id does not exist');
			}
		}
	}

	public function deleteTipoProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}