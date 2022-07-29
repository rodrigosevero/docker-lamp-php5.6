<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class PermissaoTable
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

	public function getPermissaosAtivas()
	{
		$sql = 'select a.* from permissao a where a.del = 0 ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getPermissao($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function savePermissao(Permissao $permissao)
	{
		$data = array(
				'descricao' => $permissao->descricao,
				'ordem' => $permissao->ordem,
				'del' => $permissao->del,
		);

		$id = (int) $permissao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getpermissao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('permissao id does not exist');
			}
		}
	}

	public function deletePermissao($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}