<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class FuncionalidadeTable
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
	
	public function getFuncionalidade($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('funcionalidade_id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveFuncionalidade(Funcionalidade $funcionalidade)
	{
		$data = array(
				'funcionalidade_nome' => $funcionalidade->funcionalidade_nome,
				'funcionalidade_pai' => $funcionalidade->funcionalidade_pai,
		);

		$id = (int) $funcionalidade->funcionalidade_id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getFuncionalidade($id)) {
				$this->tableGateway->update($data, array('funcionalidade_id' => $id));
			} else {
				throw new \Exception('area id does not exist');
			}
		}
	}

	public function deleteFuncionalidade($id)
	{
		$this->tableGateway->delete(array('funcionalidade_id' => (int) $id));
	}
}