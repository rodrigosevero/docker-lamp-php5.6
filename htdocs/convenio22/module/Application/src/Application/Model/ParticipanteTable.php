<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class ParticipanteTable
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

	public function getParticipantes($cpf)
	{
		$sql = "select a.*, (select count(id) from participante_projeto where usuario_id = a.id) as total from participantes a where a.del = 0";
		if($cpf){	$sql .= " and cpf = '".$cpf."'"; }
		if($cpf){	$sql .= " or nome like '%".$cpf."%'"; }
		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getParticipanteCpf($cpf)
	{

		/*$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('del' => 0, 'cpf' => $cpf));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $cpf");
		}
		return $row;*/

		$sql = "select a.* from participantes a where a.del = 0 and cpf = '".$cpf."'";
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
				'senha' => $participante->senha,
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
			if ($this->getUsuarioProjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_projeto id does not exist');
			}
		}
	}

	public function deleteParticipante($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}


}
