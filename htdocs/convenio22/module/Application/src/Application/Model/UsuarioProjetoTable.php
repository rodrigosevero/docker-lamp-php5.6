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

	public function getUsuariosProjetosByUsuariosAtivos($status_busca, $tipo_vinculo_busca)
	{
		$sql = 'select a.usuario_id, a.projeto_id, a.representante_id, b.*, p.descricao as permissao
						from usuario_projeto a
							left join usuario b on b.id = a.usuario_id
							left join projeto e on e.id = a.projeto_id
							left join permissao p on p.id = b.permissao
						where a.del = 0 and b.del = 0 and e.inativo = 0';
        if ($status_busca){ $sql .= " and b.status = '".$status_busca."'";}
        if ($tipo_vinculo_busca){ $sql .= " and b.vinculo = '".$tipo_vinculo_busca."'";}
        $sql .= " order by b.nome ASC";
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
		$sql = 'select a.*, b.id as usuario_id, b.nome as colaborador, b.permissao, b.email, b.cpf, b.tel_fixo,b.status, b.unidade_lotacao, d.nome as representante, c.descricao as vinculo,
		 				(select count(id) from atividade_projeto_usuario where projeto_id = '.$projeto_id.' and usuario_id = a.usuario_id) as total_atividades from usuario_projeto a
									 left join usuario b on b.id = a.usuario_id
									 left join tipo_vinculo c on c.id = b.vinculo
									 left join representante_tce d on d.id = b.representante_id
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
	 	$sql = 'select a.*, b.descricao from usuario_projeto a left join projeto b on b.id = a.projeto_id where b.projeto_especial = 0 and a.del = 0 and a.usuario_id = '.$usuario_id.'';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getUsuarioProjetosEspecificosAtivos($usuario_id)
	{
		$sql = 'select a.*, b.descricao from usuario_projeto a left join projeto b on b.id = a.projeto_id where b.projeto_especial = 1 and a.del = 0 and a.usuario_id = '.$usuario_id.'';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuarioProjetoEspecificoAtivos($usuario_id, $projeto_id)
	{
		$sql = 'select a.*, b.descricao from usuario_projeto a left join projeto b on b.id = a.projeto_id where b.projeto_especial = 1 and a.del = 0 and a.usuario_id = '.$usuario_id.' and projeto_id = '.$projeto_id.'';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuarioProjetosEspecificosAtivos1($usuario_id, $projeto_id)
	{
		$sql = 'select a.*, b.descricao from usuario_projeto a left join projeto b on b.id = a.projeto_id where b.projeto_especial = 1 and a.del = 0 and a.usuario_id = '.$usuario_id.' and a.projeto_id = '.$projeto_id.'';

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
