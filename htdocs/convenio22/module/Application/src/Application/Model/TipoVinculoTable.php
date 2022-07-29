<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class TipoVinculoTable
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

	public function getTipoVinculosAtivos()
	{
		$sql = 'select a.* from tipo_vinculo a where a.del = 0 order by a.descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getTipoVinculo($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveTipoVinculo(TipoVinculo $tipo_vinculo)
	{
		$data = array(
				'descricao' => $tipo_vinculo->descricao,
				'del' => $tipo_vinculo->del,
		);

		$id = (int) $tipo_vinculo->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getTipoVinculo($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('tipo_vinculo id does not exist');
			}
		}
	}

	public function deleteTipoVinculo($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}