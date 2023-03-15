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

	public function getParticipantes()
	{
		$sql = "select a.* from participantes a where a.del = 0";
	
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
				'del' => $participante->del,

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

	public function deleteParticipante($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}