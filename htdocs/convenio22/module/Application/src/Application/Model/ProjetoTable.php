<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ProjetoTable
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

	public function getProjetosByAtividadePTC($atividade_ptc_id)
	{
		$sql = 'select a.*, b.descricao as atividade, c.descricao as projeto, c.id as projeto_id, d.descricao as meta 
				from relatorio_cumprimento_objeto_atividade a 
						left join atividade_projeto b on b.id = a.atividade_epe_id 
				   	 	left join projeto c on c.id = b.projeto_id 
					 	left join area d on d.id = c.area_id 
				where a.del = 0 and a.atividade_ptc_id = '.$atividade_ptc_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getProjetos()
	{
		$sql = 'SELECT a.*, a.id as projeto_id, b.meta as meta FROM projeto a left join area b on b.id = a.area_id WHERE a.del=0 order by meta, a.descricao ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getProjetosPai()
	{
		$sql = "SELECT a.*, b.descricao as area FROM   projeto a left join  area b on b.id = a.area_id where a.del = 0 order by area asc, a.descricao asc";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getProjetosAtivosDiferenteMatriz()
	{
		$sql = 'select a.* from projeto a 
								left join area b on b.id = a.area_id 
								left join nucleo c on c.id = a.nucleo_id
								where a.del = 0 and a.pai = 0 and a.tipo_projeto != 9 order by b.descricao asc, c.descricao, a.descricao asc ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}


    public function getProjetosAtivosDiferenteMatrizByArea($area_id)
    {
        $sql = 'select a.* from projeto a 
								left join area b on b.id = a.area_id 
								left join nucleo c on c.id = a.nucleo_id
								where a.del = 0 and a.pai = 0 and a.tipo_projeto != 9 and a.area_id = '.$area_id.' order by b.descricao asc, c.descricao, a.descricao asc ';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

    public function getProjetosAtivosDiferenteMatrizByCoordenadorNucleo($area_id, $nucleo_id)
    {
        $sql = 'select a.* from projeto a 
								left join area b on b.id = a.area_id 
								left join nucleo c on c.id = a.nucleo_id
								where a.del = 0 and a.pai = 0 and a.tipo_projeto != 9 and a.area_id = '.$area_id.' and a.nucleo_id = '.$nucleo_id.' order by b.descricao asc, c.descricao, a.descricao asc ';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }
	
	public function getProjetosAtivosFilho($pai)
	{
		$sql = 'select a.*, 
		(select count(id) from usuario_projeto where del = 0 and projeto_id = a.id group by projeto_id) as total_usuarios,
		(select count(id) from atividade_projeto where del = 0 and projeto_id = a.id group by projeto_id) as total_atividades,
		(select count(id) from participante_projeto where del = 0 and projeto_id = a.id group by projeto_id) as total_participantes 		
		from projeto a where a.del = 0 and a.pai = '.$pai.' and a.tipo_projeto != 9';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	

	public function getProjetosAtivos()
	{
		$sql = 'select * from projeto where inativo = 0 and del = 0 and tipo_projeto != 9';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getProjetosAtivosbyArea($area_id)
	{
		$sql = 'select * from projeto where del = 0 and area_id = '.$area_id.' order by descricao ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getProjetosInativos()
	{
		$sql = 'select * from projeto a 
				where a.del = 0 and a.tipo_projeto != 9 and a.inativo = 1';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadeProjetoUsuario($usuario_id)
	{
		$sql = 'select atividade_projeto_id from atividade_projeto_usuario a where a.del = 0 and a.usuario_id = '.$usuario_id.'';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function deleteAtividadeProjetoUsuario($usuario_id,$projeto_id)
	{
		$sql = 'delete from atividade_projeto_usuario where projeto_id = '.$projeto_id.' and usuario_id = '.$usuario_id.'';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getProjetosByTipoProjeto($tipo_projeto_id)
	{
		$sql = 'select * from projeto a
				where a.del = 0 and a.tipo_projeto = '.$tipo_projeto_id.' and a.inativo = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}


    public function getProjetosByTipoProjetoByArea($tipo_projeto_id, $area_id)
    {
        $sql = 'select * from projeto a
				where a.del = 0 and a.tipo_projeto = '.$tipo_projeto_id.' and a.area_id = '.$area_id.' and a.inativo = 0';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }


    public function getProjetosByTipoProjetoByCoordenadorNucleo($tipo_projeto_id, $area_id, $nucleo_id)
    {
        $sql = 'select * from projeto a
				where a.del = 0 and a.tipo_projeto = '.$tipo_projeto_id.' and a.area_id = '.$area_id.' and nucleo_id = '.$nucleo_id.' and a.inativo = 0';

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }

	public function getProjetosInativosByTipoProjeto($tipo_projeto_id)
	{
		$sql = 'select * from projeto a
				where a.del = 0 and a.tipo_projeto = '.$tipo_projeto_id.' and a.inativo = 1';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function saveAtividadeProjetoUsuario($atividade_projeto_id, $projeto_id, $usuario_id)
	{
		$sql = 'insert into atividade_projeto_usuario (atividade_projeto_id, projeto_id, usuario_id, del) values ('.$atividade_projeto_id.','.$projeto_id.','.$usuario_id.', 0)';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}
	
	
	public function getProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveProjeto(Projeto $projeto)
	{
		
		if($projeto->nucleo_id != ''){ $nucleo_id = $projeto->nucleo_id; } else { $nucleo_id = 0; }
		$data = array(
				'descricao' => $projeto->descricao,
				
				'pai'=>$projeto->pai,
    			'coordenador_tce_mpc' => $projeto->coordenador_tce_mpc,
				'data_inicio' => $projeto->data_inicio,
				'data_fim' => $projeto->data_fim,
				'carga_horaria' => $projeto->carga_horaria,
				'vagas_ofertadas' => $projeto->vagas_ofertadas,
				'entidade_certificadora' => $projeto->entidade_certificadora,
				'status' => $projeto->status,
				'arquivo' => $projeto->arquivo,
				'tipo_projeto' => $projeto->tipo_projeto,
				'area_id' => $projeto->area_id,
				'nucleo_id' => $nucleo_id,
				'coordenador_id' => $projeto->coordenador_id,
				'representante_tce_id' => $projeto->representante_tce_id,
				'usuario_id' => $projeto->usuario_id,
				'data' => $projeto->data,
				'hora' => $projeto->hora,
				'inativo' => $projeto->inativo,
				'del' => $projeto->del,
				'projeto_especial' => $projeto->projeto_especial,							
		);

		$id = (int) $projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getprojeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('projeto id does not exist');
			}
		}
	}

	public function deleteProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}