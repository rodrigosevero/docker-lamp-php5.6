<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class RepresentanteProjetoTable
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

	public function getRepresentanteProjetosAtivos()
	{
		$sql = 'select a.* from representante_tce_projeto a where a.del = 0 order by nome';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getRepresentantesByProjeto($projeto_id)
	{
		$projeto_id  = (int) $projeto_id;
		$rows = $this->tableGateway->select(array('projeto_id' => $projeto_id));
		//$row = $rowset->current();
		if (!$rows) {
			throw new \Exception("Could not find row $projeto_id");
		}
		return $rows;
	}
	
	public function getRepresentanteProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveRepresentanteProjeto(RepresentanteProjeto $representante_projeto)
	{
		$data = array(
				'representante_id' => $representante_projeto->representante_id,
				'projeto_id' => $representante_projeto->projeto_id,
				'del' => $representante_projeto->del,
		);

		$id = (int) $representante_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getrepresentante_projeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('representante_projeto id does not exist');
			}
		}
	}

	public function deleteRepresentantesProjeto($id)
	{
		$this->tableGateway->delete(array('projeto_id' => (int) $id));
	}

	public function deleteRepresentanteProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}