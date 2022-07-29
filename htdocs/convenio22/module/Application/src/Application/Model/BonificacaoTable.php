<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class BonificacaoTable
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

	public function getBonificacoes()
	{
		$sql = 'select a.* from bonificacao a where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getBonificacoesGeral()
	{
		$sql = 'select a.*, c.descricao as area, (a.valor_inicial * meses) as valor_total, b.nome as colaborador,  b.data_admissao, b.data_inatividade
		from bonificacao a 
		left join usuario b  on b.bonificacao_id = a.id  
		left join area c on c.id = a.area_id
		where a.del = 0 order by descricao asc';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getBonificacao($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row ".$id);
		}
		return $row;
	}

	public function saveBonificacao(bonificacao $bonificacao)
	{
		$data = array(
			'descricao' => $bonificacao->descricao,
			'carga_horaria' => $bonificacao->carga_horaria,
			'meses' => $bonificacao->meses,
			'codigo' => $bonificacao->codigo,
			'valor_inicial' => $bonificacao->valor_inicial,
			'valor_medio' => $bonificacao->valor_medio,
			'valor_final' => $bonificacao->valor_final,
			'quantidade' => $bonificacao->quantidade,			
			'requisitos' => $bonificacao->requisitos,			
		);

		$id = (int) $bonificacao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getBonificacao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('funcao id does not exist');
			}
		}
	}

	public function deleteBonificacao($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
