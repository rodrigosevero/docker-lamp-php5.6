<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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
		$sql = 'select a.*, d.nome as representante,
                (select descricao from area where id = a.area_id) as area1,
                (select descricao from area where id = a.area_id2) as area2,
                (select descricao from permissao where id = a.permissao) as tipo,
	 	 		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos 
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join representante_tce d on d.id = a.representante_id
		  where a.del = 0 order by nome ASC';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getUsuariosColaboradoresRelatorio()
	{
		$sql = 'select a.*, d.nome as representante,
                (select descricao from area where id = a.area_id) as area1,
                (select descricao from area where id = a.area_id2) as area2,
                (select descricao from permissao where id = a.permissao) as tipo,
	 	 		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos 
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join representante_tce d on d.id = a.representante_id
		  where a.del = 0 and status = "ativo" and vinculo = 1 order by nome ASC';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

    public function getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca, $meta)
    {
        $sql = 'select a.*, d.nome as representante, f.descricao as funcao, f.carga_horaria, f.meses,
                (select descricao from area where id = a.area_id) as area1,
                (select descricao from area where id = a.area_id2) as area2,
                (select descricao from permissao where id = a.permissao) as tipo,
				(select descricao from bolsa where id = a.bolsa_id) as bolsa,
				(select descricao from bonificacao where id = a.bonificacao_id) as bonificacao,
	 	 		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos,
				(select count(id) from representante_usuario where usuario_id = a.id) as historico_representantes,
				(select count(id) from area_funcao_historico where colaborador_id = a.id) as historico_funcoes
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join representante_tce d on d.id = a.representante_id
			  left join area_funcao e on e.id = a.cargo_funcao
			  left join funcao f on f.id = e.funcao_id
		  where a.del = 0 and permissao != 15';
		  if ($meta){ $sql .= " and a.area_id = '".$meta."'";}
		  if ($status_busca){ $sql .= " and status = '".$status_busca."'";}
          if ($tipo_vinculo_busca){ $sql .= " and vinculo = '".$tipo_vinculo_busca."'";}
		  $sql .= ' order by nome ASC';
          //echo $sql;

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

	public function getUsuariosColaboradores2($status_busca, $tipo_vinculo_busca, $meta)
    {
		$sql = 'select a.*, d.nome as representante, f.descricao as funcao, f.carga_horaria, f.meses,
		p.descricao as tipo
                
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join representante_tce d on d.id = a.representante_id
			  left join area_funcao e on e.id = a.cargo_funcao
			  left join funcao f on f.id = e.funcao_id
			  left join permissao p on p.id = a.permissao

		  where a.del = 0';
		  if ($meta){ $sql .= " and a.area_id = '".$meta."'";}
		  if ($status_busca){ $sql .= " and status = '".$status_busca."'";}
          if ($tipo_vinculo_busca){ $sql .= " and vinculo = '".$tipo_vinculo_busca."'";}
		  $sql .= ' order by nome ASC';
          //echo $sql;

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }


    public function getUsuariosEstagiarios()
    {
        $sql = 'select a.*, d.nome as representante,
                (select descricao from area where id = a.area_id) as area1,
                (select descricao from area where id = a.area_id2) as area2,
                (select descricao from permissao where id = a.permissao) as tipo,
	 	 		(select count(up.id) from usuario_projeto up left join projeto p on p.id = up.projeto_id  where up.del = 0 and  p.inativo = 0 and  up.usuario_id = a.id) as projetos 
     		from usuario a 
			  left join area b on b.id=a.area_id 
			  left join tipo_vinculo c on c.id = a.vinculo
			  left join representante_tce d on d.id = a.representante_id
		  where a.del = 0 and a.permissao = 20 and a.status = "ativo" order by nome ASC';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

    public function getRelatorioMensaldeAtividades($mes, $ano)
    {
        $sql = 'select a.nome,   (select concat(data_relatorio, "||", texto) as relatorio from usuario_atividade where usuario_id = a.id and MONTH(data_relatorio) = "'.$mes.'" and  YEAR(data_relatorio) = "'.$ano.'" order by id desc limit 1) as relatorio
              from usuario a 			  
		  where a.del = 0 order by nome ASC';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

	public function getUsuariosAtivos()
	{
		$sql = 'select * from usuario where del = 0 order by nome';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}


	public function getUsuariosBonificacao()
	{
		$sql = 'SELECT a.nome, b.* FROM usuario a 
		left join bonificacao b on b.id = a.bonificacao_id
		where a.bonificacao_id is not NULL and a.bonificacao_id != ""';
		echo $sql;
		die;
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	
	public function getRepresentantesUsuario($usuario_id)
	{
		$sql = 'select * from representante_usuario where del = 0 and usuario_id = '.$usuario_id.' order by nome';
	
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
        //$rowset->join('representante_tce', 'representante_tce.id = usuario.representante_id', array(), 'inner');
        $row = $rowset->current();
        return $row;


	}


    public function getRepresentanteUsuario($id)
    {
        $sql = 'select b.nome from usuario a left JOIN  representante_tce b on b.id=a.representante_id where b.del = 0 and a.id = '.$id.'';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
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




    public function getUsuarioLogin1($login, $senha)
    {


        $sql = 'select a.*, b.id as nucleo from usuario a left join nucleo b on b.coordenador_id = a.id where a.del = 0 and a.cpf = "'.$login.'" and senha = "'.$senha.'" and status = "ativo" ';
        $statement = $this->tableGateway->adapter->query($sql);
        $rowset = $statement->execute();
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
                'area_id2' => $usuario->area_id2,
				'vinculo' => $usuario->vinculo,
                'curso_estagio' => $usuario->curso_estagio,
                'professor_estagio' => $usuario->professor_estagio,
				'instituicao' => $usuario->instituicao,
				'cargo_funcao' => $usuario->cargo_funcao,
				'bonificacao_id' => $usuario->bonificacao_id,
				'bonificacao_projeto' => $usuario->bonificacao_projeto,
				'bolsa_id' => $usuario->bolsa_id,
				'bolsa_projeto' => $usuario->bolsa_projeto,
				'unidade_lotacao' => $usuario->unidade_lotacao,
				'permissao' => $usuario->permissao,
				'del' => $usuario->del,
				'atualizado' => $usuario->atualizado,
				'data_atualizado' => $usuario->data_atualizado,
				'hora_atualizado' => $usuario->hora_atualizado,				
				'status' => $usuario->status,
				'siape' => $usuario->siape,
				'data_inatividade' => $usuario->data_inatividade,
				'data_admissao' => $usuario->data_admissao,
				'status_enquadramento_funcional' => $usuario->status_enquadramento_funcional,
				'representante_id' => $usuario->representante_id,
				'representante_id2' => $usuario->representante_id2,
				'funcao' => $usuario->funcao,
				'superadmin' => $usuario->superadmin,
				'arquivo' => $usuario->arquivo,
				'del' => '0',
				'termo_autorizacao' => $usuario->termo_autorizacao,
				'parcelas' => $usuario->parcelas,

		);

		$id = (int) $usuario->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuario($id)) {
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
	
	
	public function saveRepresentanteUsuario($usuario_id, $representante_id, $usuario_update)
	{

		$data = date('Y-m-d H:i:s');
		$sql = 'insert into representante_usuario (usuario_id, representante_id,data, usuario_update) 
		values ('.$usuario_id.', '.$representante_id.', "'.$data.'", '.$usuario_update.')';
		$statement = $this->tableGateway->adapter->query($sql);	
		return $statement->execute();
	}
	
}