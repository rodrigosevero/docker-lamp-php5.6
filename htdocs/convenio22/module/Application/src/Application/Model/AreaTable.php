<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AreaTable
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

	public function getAreasAtivas()
	{
		$sql = 'select a.*, (select count(id) from area_funcao where del = 0 and area_id = a.id) as funcoes from area a where a.del = 0 order by a.descricao';
	
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
	
	public function getArea($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveArea(Area $area)
	{
		$data = array(
				'descricao' => $area->descricao,
				'meta' => $area->meta,
				'del' => $area->del,
		);

		$id = (int) $area->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getarea($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('area id does not exist');
			}
		}
	}

	public function deleteArea($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}