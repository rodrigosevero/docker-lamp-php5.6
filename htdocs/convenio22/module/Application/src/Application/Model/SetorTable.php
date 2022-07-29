<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class SetorTable
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

	public function getSetoresAtivos()
	{
		$sql = 'select a.* from setor a where a.del = 0 order by a.descricao';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

    public function getAreaPorArea($id)
    {
        $sql = 'select a.* from area a where a.del = 0 and a.id = "'.$id.'" order by a.descricao';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }

    public function getArea1($id)
    {
        $id  = (int) $id;
        $sql = 'select a.* from area a where a.del = 0 and a.id = "'.$id.'" order by a.descricao';
        $statement = $this->tableGateway->adapter->query($sql);
        return $statement->execute();
    }

	public function getSetor($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveSetor(Setor $setor)
	{
		$data = array(
				'descricao' => $setor->descricao,
				'del' => $setor->del,
		);

		$id = (int) $setor->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getSetor($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('setor id does not exist');
			}
		}
	}

	public function deleteSetor($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
