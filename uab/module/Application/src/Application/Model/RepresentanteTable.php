<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class RepresentanteTable
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

	public function getRepresentantesAtivos()
	{
		$sql = 'select a.* from representante_tce a where a.del = 0 order by nome';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getRepresentantesByUsuarioProjeto()
	{
		$sql = 'select a.id, a.representante_id as representante_id, p.nome as descricao 
				from usuario_projeto a 
					left join representante_tce p on p.id = a.representante_id 
				where a.del = 0 group by a.representante_id';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getRepresentante($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saverepresentante(representante $representante)
	{
		$data = array(
				'nome' => $representante->nome,
				'email' => $representante->email,
				'cpf' => $representante->cpf,
				'nome_cargo' => $representante->nome_cargo,
				'funcao_confianca' => $representante->funcao_confianca,
				'telefone' => $representante->telefone,
				'orgao' => $representante->orgao,
				'setor_lotacao' => $representante->setor_lotacao,
				'del' => $representante->del,
		);

		$id = (int) $representante->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getrepresentante($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('representante id does not exist');
			}
		}
	}

	public function deleterepresentante($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}