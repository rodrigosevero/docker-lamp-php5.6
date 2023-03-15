<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UsuarioProjetoTable
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
	
	public function getUsuariosProjetosByColaborador()
	{
		$sql = 'select a.id, a.representante_id, a.usuario_id, a.projeto_id
					from usuario_projeto a 
						left join projeto pp on pp.id = a.projeto_id 					
					where a.del = 0 and pp.inativo = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuariosProjetosByUsuariosAtivos()
	{
		$sql = 'select a.usuario_id, a.projeto_id, a.representante_id, b.* 
						from usuario_projeto a 
							left join usuario b on a.usuario_id = b.id 
							left join projeto e on e.id = a.projeto_id
						where a.del = 0 and b.del = 0 and e.inativo = 0 order by b.nome';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuariosProjetosByUsuario($usuario_id)
	{
		$sql = 'select a.id, a.representante_id, a.usuario_id, a.projeto_id
					from usuario_projeto a 
						left join projeto pp on pp.id = a.projeto_id 					
					where a.del = 0 and pp.inativo = 0 and a.usuario_id ='.$usuario_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuariosProjetosAtivosByProjeto($projeto_id)
	{
		$sql = 'select * from usuario_projeto where del = 0 and projeto_id = '.$projeto_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getColaboradorPorProjeto($projeto_id)
	{
		$sql = 'select a.*, b.id as usuario_id, b.nome as colaborador, b.permissao, b.email, b.cpf, b.tel_fixo,b.status, c.descricao as vinculo from usuario_projeto a
									 left join usuario b on b.id = a.usuario_id 
									 left join tipo_vinculo c on c.id = b.vinculo
									 where a.del = 0 and b.del = 0 and a.projeto_id = '.(int)$projeto_id.' order by b.nome';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	
	public function getUsuariosProjetosAtivos()
	{
		$sql = 'select a.* from usuario_projeto a where a.del = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}	

	public function getUsuarioProjetosAtivos($usuario_id)
	{
		$sql = 'select a.* from usuario_projeto a where a.del = 0 and usuario_id = '.$usuario_id.'';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getUsuarioProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveUsuarioProjeto(UsuarioProjeto $usuario_projeto)
	{
		$data = array(
				'projeto_id' => $usuario_projeto->projeto_id,
				'usuario_id' => $usuario_projeto->usuario_id,
				'representante_id' => $usuario_projeto->representante_id,
				'del' => $usuario_projeto->del,
		);

		$id = (int) $usuario_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuarioProjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_projeto id does not exist');
			}
		}
	}

	public function deleteUsuarioProjetosByUsuario($id)
	{
		$this->tableGateway->delete(array('usuario_id' => (int) $id));
	}

	public function deleteUsuarioProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}