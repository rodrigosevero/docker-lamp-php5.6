<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AtividadePTCTable
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
	
	public function getAtividades()
	{
		$sql = "SELECT * FROM `atividade_ptc`";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getAtividadesPTCAtivasByArea($area_id)
	{
		$sql = 'select * from atividade_ptc where del = 0 and area_id = '.$area_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getAtividadesPTCAtivasByAreaBySubmeta($area_id, $submeta_id)
	{
		$sql = 'select * from atividade_ptc where del = 0 and status > 1 and area_id = '.$area_id.' and submeta_id = '.$submeta_id.' order by atividade';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadesPTCAtivas()
	{
		$sql = 'select a.*, b.descricao as area, c.codigo as submeta 
	  					from atividade_ptc a 
						left join area b on b.id = a.area_id 
						left join submeta c on c.id = a.submeta_id 
						where a.del = 0 order by b.descricao, c.codigo';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getAtividadesPTCConcluidas()
	{
		$sql = 'select * from atividade_ptc where  status  = 1 and del = 0 ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadePTC($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveAtividadePTC(AtividadePTC $atividade_ptc)
	{		
		$data = array(
				
				'area_id' => $atividade_ptc->area_id,
				'submeta_id' => $atividade_ptc->submeta_id,
				'etapa' => $atividade_ptc->etapa,
				'atividade' => $atividade_ptc->atividade,
				'inicio' => $atividade_ptc->inicio,
				'fim' => $atividade_ptc->fim,
				'indicador_unid' => $atividade_ptc->indicador_unid,
				'indicador_quant' => $atividade_ptc->indicador_quant,
				'indicador_desempenho' => $atividade_ptc->indicador_desempenho,
				'fluxo_continuo' => $atividade_ptc->fluxo_continuo,
				'data_inicio' => $atividade_ptc->data_inicio,
				'data_fim' => $atividade_ptc->data_fim,
				'relatorio' => $atividade_ptc->relatorio,
				'resultados' => $atividade_ptc->resultados,
				'produtos' => $atividade_ptc->produtos,				
				'status' => $atividade_ptc->status,
				'del' => $atividade_ptc->del,
		);

		$id = (int) $atividade_ptc->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getAtividadePTC($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Atividade PTC id does not exist');
			}
		}
	}

	public function deleteAtividadesPTCByArea($id)
	{
		$this->tableGateway->delete(array('area_id' => (int) $id));
	}

	public function deleteAtividadePTC($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}