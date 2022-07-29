<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class RepresentanteUsuarioTable
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
	
	public function saveRepresentanteUsuario(RepresentanteUsuario $representante_usuario)
	{
		$data = array(
				'usuario_id' => $representante_usuario->usuario_id,
				'representante_id' => $representante_usuario->representante_id,
				'data' => $representante_usuario->data,
				'del' => $representante_usuario->del,
		);
		$this->tableGateway->insert($data);
		$id = $this->tableGateway->getLastInsertValue();
		return $id;
	}
	
	
}