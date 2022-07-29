<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AtividadeProjetoTable
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

	public function getAtividadesMensaisAtivasByProjeto($projeto_id, $data_inicio, $data_fim)
	{
		$sql = 'select * from atividade_projeto where del = 0  and projeto_id = '.$projeto_id.' and dt_inicial <= "'.$data_inicio.'" and dt_final >= "'.$data_fim.'" order by id desc';

		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getAtividadesMatriz($projeto_id)
	{
		//$sql = 'select * from atividade_projeto where del = 0  and projeto_id = '.$projeto_id.' and dt_inicial <= "'.$data_inicio.'" and dt_final >= "'.$data_fim.'" order by id desc';

$sql = 'select * from atividade_projeto where del = 0 order by id desc';

		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}


	
	public function getAtividades()
	{
		$sql = "SELECT * FROM `atividade_projeto` WHERE `descricao` LIKE '%?%'";
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadesAtivasByProjeto($projeto_id)
	{
		$sql = 'select * from atividade_projeto where del = 0 and projeto_id = '.$projeto_id.' ORDER BY descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}


    public function getMetaAtividadesAtivasByProjeto($id)
    {
        $sql = 'select a.*, b.atividade as atividade, c.descricao as area1
																			from relatorio_cumprimento_objeto_atividade a 
																			left join atividade_ptc b on b.id = a.atividade_ptc_id 
																			left join area c on c.id = b.area_id
																			where a.del = 0 and a.atividade_epe_id = '.$id;

        $statement = $this->tableGateway->adapter->query($sql);

        return $statement->execute();
    }
	
	
	public function getAtividadesAtivasPorUsuario($usuario_id)
	{
		$sql = 'select a.*, b.descricao from atividade_projeto_usuario a left join atividade_projeto b on b.id = a.atividade_projeto_id where a.del = 0 and a.usuario_id = '.$usuario_id.' ORDER BY projeto_id, descricao';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}
	
	public function getAtividadeProjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveAtividadeProjeto(AtividadeProjeto $atividade_projeto)
	{
		$data = array(
				'projeto_id' => $atividade_projeto->projeto_id,
				'dt_inicial' => $atividade_projeto->dt_inicial,
				'dt_final' => $atividade_projeto->dt_final,
				'ptc' => $atividade_projeto->ptc,
				'descricao' => $atividade_projeto->descricao,
				'prev_inicio' => $atividade_projeto->prev_inicio,
				'prev_fim' => $atividade_projeto->prev_fim,
				'prazo' => $atividade_projeto->prazo,
				'epe' => $atividade_projeto->epe,
				//'inativo' => $atividade_projeto->inativo,
				'del' => $atividade_projeto->del,
		);

		$id = (int) $atividade_projeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getAtividadeProjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('atividade_projeto id does not exist');
			}
		}
	}

	public function deleteAtividadesProjeto($id)
	{
		$this->tableGateway->delete(array('projeto_id' => (int) $id));
	}

	public function deleteAtividadeProjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}