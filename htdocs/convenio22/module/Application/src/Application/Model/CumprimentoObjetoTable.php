<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class CumprimentoObjetoTable
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

	public function getCumprimentoObjetosDESC()
	{
		$sql = 'select * from relatorio_cumprimento_objeto order by id desc ';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getCumprimentoObjetosAtivos()
	{
		$sql = 'select a.* from relatorio_cumprimento_objeto a where a.del = 0';
	
		$statement = $this->tableGateway->adapter->query($sql);
	
		return $statement->execute();
	}

	public function getLastCumprimentoObjetoByAtividade($atividade_id)
	{
		 $sql = 'select a.*  from relatorio_cumprimento_objeto a where a.del = 0 and  a.atividade_id = "'.$atividade_id.'" order by id desc limit 1';
	
		$statement = $this->tableGateway->adapter->query($sql);
		$result = $statement->execute();
	
		return $result->current();
	}
	
	public function getCumprimentoObjeto($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function saveCumprimentoObjeto(CumprimentoObjeto $cumprimento_objeto)
	{
		$data = array(
				'atividade_id' => $cumprimento_objeto->atividade_id,
				'parcial_total' => $cumprimento_objeto->parcial_total,
				'data' => $cumprimento_objeto->data,
				'atividade_1' => $cumprimento_objeto->atividade_1,
				'atividade_1_justifique' => $cumprimento_objeto->atividade_1_justifique,
				'atividade_2' => $cumprimento_objeto->atividade_2,
				'atividade_2_justifique' => $cumprimento_objeto->atividade_2_justifique,
				'acoes_executadas' => $cumprimento_objeto->acoes_executadas,
				'avaliacao' => $cumprimento_objeto->avaliacao,
				'quantitativos_executados' => $cumprimento_objeto->quantitativos_executados,				
				'quantitativos_executados_justifique' => $cumprimento_objeto->quantitativos_executados_justifique,
				'principais_resultados' => $cumprimento_objeto->principais_resultados,

				'porcentagem_realizacao' => $cumprimento_objeto->porcentagem_realizacao,
				'resultados_esperados' => $cumprimento_objeto->resultados_esperados,
				'produtos_esperados' => $cumprimento_objeto->produtos_esperados,


                'atividades_ensino_planejadas' => $cumprimento_objeto->atividades_ensino_planejadas,
                'atividades_ensino_planejadas_resultados' => $cumprimento_objeto->atividades_ensino_planejadas_resultados,

                'atividades_pesquisa_planejadas' => $cumprimento_objeto->atividades_pesquisa_planejadas,
                'atividades_pesquisa_planejadas_resultados' => $cumprimento_objeto->atividades_pesquisa_planejadas_resultados,

                'atividades_extensao_planejadas' => $cumprimento_objeto->atividades_extensao_planejadas,
                'atividades_extensao_planejadas_resultados' => $cumprimento_objeto->atividades_extensao_planejadas_resultados,

                'restricoes' => $cumprimento_objeto->restricoes,
				'usuario_id' => $cumprimento_objeto->usuario_id,
				'data_alteracao' => $cumprimento_objeto->data_alteracao,
				'hora_alteracao' => $cumprimento_objeto->hora_alteracao,
				'resumo_relatorio' => $cumprimento_objeto->resumo_relatorio,
				'sintese_acao' => $cumprimento_objeto->sintese_acao,
				'del' => $cumprimento_objeto->del,
		);

		$id = (int) $cumprimento_objeto->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getCumprimentoObjeto($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('area id does not exist');
			}
		}
	}

	public function deleteCumprimentoObjeto($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}