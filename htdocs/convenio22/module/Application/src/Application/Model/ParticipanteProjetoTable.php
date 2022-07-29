<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ParticipanteProjetoTable
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

	public function getParticipantesProjetosAtivosByProjeto($id)
	{
		$sql = "select a.* from participante_projeto a where a.del = 0 and projeto_id = ".(int)$id."";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}	
	
	public function getParticipantesPorProjeto($projeto_id)
	{
		/*$sql = 'select a.*, b.id as usuario_id, b.nome as colaborador, b.permissao, b.email, b.cpf, b.tel_fixo,b.status, b.unidade_lotacao, d.nome as representante, c.descricao as vinculo,
		 				(select count(id) from atividade_projeto_usuario where projeto_id = '.$projeto_id.' and usuario_id = a.usuario_id) as total_atividades from participante_projeto a
									 left join usuario b on b.id = a.usuario_id 
									 left join tipo_vinculo c on c.id = b.vinculo
									 left join representante_tce d on d.id = b.representante_id
									 where a.del = 0 and b.del = 0 and a.projeto_id = '.(int)$projeto_id.' order by b.nome';*/
									 
									 $sql = 'select a.*, a.id as participante_projeto_id, b.*, b.nome as colaborador 
									 from participante_projeto a
									 left join participantes b on b.id = a.usuario_id 
									 where a.del = 0 and b.del = 0 and a.projeto_id = '.(int)$projeto_id.' order by b.nome';
									 
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	
	public function getParticipante($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	
	public function saveParticipante(Participante $participante)
	{
		$data = array(
				'nome' => $participante->nome,
				'email' => $participante->email,
				'tel_fixo' => $participante->tel_fixo,
				'tel_movel' => $participante->tel_movel,
				'cpf' => $participante->cpf,
				'data_nascimento' => $participante->data_nascimento,
				'nivel_escolaridade' => $participante->nivel_escolaridade,
				'del' => 0,

		);
		

		$id = (int) $participante->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getParticipante($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Participante id does not exist');
			}
		}
	}
	
	
	public function getParticipanteProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	
	public function saveParticipanteProjeto(ParticipanteProjeto $participante_projeto)
	{
		$data = array(
				'projeto_id' => $participante_projeto->projeto_id,
				'usuario_id' => $participante_projeto->usuario_id,
				'representante_id' => $participante_projeto->representante_id,
				'del' => $participante_projeto->del,
		);

		$id = (int) $participante_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getParticipanteProjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_projeto id does not exist');
			}
		}
	}
	
	public function getParticipantesProjetosByUsuario($usuario_id)
	{
	$sql = 'select a.id, a.representante_id, a.usuario_id, a.projeto_id
					from participante_projeto a 
						left join projeto pp on pp.id = a.projeto_id 					
					where a.del = 0 and a.usuario_id ='.$usuario_id;

	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	
	public function getParticipantesProjetosByColaborador()
	{
		echo $sql = 'select a.id, a.representante_id, a.usuario_id, a.projeto_id
					from participante_projeto a 
						left join projeto pp on pp.id = a.projeto_id 					
					where a.del = 0';
	die;
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	
	public function deleteProjetoParticipante($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}


	public function updateProjetoParticipanteStatus($id,$status)
	{
		$data = array(
			'concluido' => $status,			
		);
		$this->tableGateway->update($data, array('id' => $id));
	}

}