<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UsuarioTable
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

	public function getUsuariosColaboradores()
	{
		$sql = 'select a.*, d.nome as representante, e.descricao as permissao,
	 	 		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos,
				(select nome from usuario where id = a.representante_id ) as representante 
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join permissao e on e.id = a.permissao
			  left join representante_tce d on d.id = a.representante_id
		  where a.del = 0 order by nome ASC';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuariosColaboradoresPorRepresentante($representante_id)
	{
		// $sql = 'select a.*, d.nome as representante, e.descricao as permissao,
		//  		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos,
		// 		(select nome from usuario where id = a.representante_id ) as representante 
		// 	from usuario a 
		// 	  left join area b on b.id=a.area_id 
		// 	  left join tipo_vinculo c on c.id = a.vinculo
		// 	  left join permissao e on e.id = a.permissao
		// 	  left join representante_tce d on d.id = a.representante_id
		//   where a.del = 0 and a.representante_id =  ' . $representante_id . ' order by nome ASC ';
		//  $representante_id;

		$sql = 'select a.* from usuario a where a.del = 0 and representante_id = ' . $representante_id . ' order by nome asc';
		//   echo $sql; die;

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}


	public function getUsuariosAtivos()
	{
		$sql = 'select * from usuario where del = 0 order by nome';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuariosRepresentantes()
	{
		$sql = 'select * from usuario where del = 0 and is_representante = 1 order by nome';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuariosByPermissao($perfil_id)
	{
		$perfil_id  = (int) $perfil_id;
		$rowset = $this->tableGateway->select(array('permissao' => $perfil_id));

		if (!$rowset) {
			throw new \Exception("Could not find row with $perfil_id");
		}
		return $rowset;
	}

	public function getUsuario($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function getRepresentante($representante_id)
	{
		$representante_id  = (int) $representante_id;
		$rowset = $this->tableGateway->select(array('id' => $representante_id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $representante_id");
		}
		return $row;
	}

	public function getUsuarioEmail($email)
	{
		$rowset = $this->tableGateway->select(array('email' => $email));

		return count($rowset);
	}

	public function getUsuarioLogin($login, $senha)
	{
		$rowset = $this->tableGateway->select(array('cpf' => $login, 'senha' => $senha));
		$row = $rowset->current();

		return $row;
	}

	public function getUsuarioCpf($cpf)
	{
		$rowset = $this->tableGateway->select(array('cpf' => $cpf));
		$row = $rowset->current();

		return $row;
	}


	public function saveUsuario(Usuario $usuario)
	{
		$data = array(
			'nome' => $usuario->nome,
			'email' => $usuario->email,
			'tel_fixo' => $usuario->tel_fixo,
			'tel_movel' => $usuario->tel_movel,
			'cpf' => $usuario->cpf,
			'cnpj' => $usuario->cnpj,
			'razao_social' => $usuario->razao_social,
			'senha' => $usuario->senha,
			'area_id' => $usuario->area_id,
			'vinculo' => $usuario->vinculo,
			'instituicao' => $usuario->instituicao,
			'curso' => $usuario->curso,
			'polo' => $usuario->polo,
			'tipo_processo_seletivo' => $usuario->tipo_processo_seletivo,
			'nu_edital' => $usuario->nu_edital,
			'cargo_funcao' => $usuario->cargo_funcao,
			'unidade_lotacao' => $usuario->unidade_lotacao,
			'permissao' => $usuario->permissao,
			'del' => $usuario->del,
			'atualizado' => $usuario->atualizado,
			'data_atualizado' => $usuario->data_atualizado,
			'hora_atualizado' => $usuario->hora_atualizado,
			'status' => $usuario->status,
			//'data_inatividade' => $usuario->data_inatividade,
			'data_admissao' => $usuario->data_admissao,
			'status_enquadramento_funcional' => $usuario->status_enquadramento_funcional,
			'funcao' => $usuario->funcao,
			'superadmin' => $usuario->superadmin,
			'representante_id' => $usuario->representante_id,
			'is_representante' => $usuario->is_representante,
			'arquivo' => $usuario->arquivo,
			'del' => '0',
		);


		$id = (int) $usuario->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuario($id)) {
				// 		print_r($data);
				// die;
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Usuario id does not exist');
			}
		}
	}

	public function deleteUsuario($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
