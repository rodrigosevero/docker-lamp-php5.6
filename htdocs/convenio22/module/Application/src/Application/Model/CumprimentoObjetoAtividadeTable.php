<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class CumprimentoObjetoAtividadeTable
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

	public function getCumprimentoObjetoAtividadesAtivos()
	{
		$sql = 'select a.* from relatorio_cumprimento_objeto_atividade a where a.del = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getCumprimentoObjetoAtividadesAtivosByAtividadePTC($atividade_id)
	{
		$sql = 'select a.* from relatorio_cumprimento_objeto_atividade a where a.del = 0 AND atividade_ptc_id = '.$atividade_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getCumprimentoObjetoAtividadesAtivosByProjeto($projeto_id)
	{
		$sql = 'select a.* from relatorio_cumprimento_objeto_atividade a where a.del = 0 AND area_id = '.$projeto_id;
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getCumprimentoObjetoAtividade($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveCumprimentoObjetoAtividade(CumprimentoObjetoAtividade $cumprimento_objeto_atividade)
	{
		$data = array(
				'area_id' => $cumprimento_objeto_atividade->area_id,
				'atividade_epe_id' => $cumprimento_objeto_atividade->atividade_epe_id,
				'atividade_ptc_id' => $cumprimento_objeto_atividade->atividade_ptc_id,
				'del' => $cumprimento_objeto_atividade->del,
		);

		$id = (int) $cumprimento_objeto_atividade->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getCumprimentoObjetoAtividade($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('area id does not exist');
			}
		}
	}

	public function deleteCumprimentoObjetoAtividade($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}