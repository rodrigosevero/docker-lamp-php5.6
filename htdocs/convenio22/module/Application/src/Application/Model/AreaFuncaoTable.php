<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class AreaFuncaoTable
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

	public function getAreaFuncoes($id)
	{
		$sql = 'select a.*, b.codigo, b.descricao as funcao, c.nome as colaborador, b.carga_horaria, b.meses
				from area_funcao a 
				left join funcao b on b.id = a.funcao_id 
				left join usuario c on c.id = a.colaborador_id 
				where a.del = 0 and a.area_id = '.$id.' order by b.codigo asc ';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getAreaFuncoesGeral()
	{
		$sql = 'select a.*, b.codigo, d.descricao as area, b.descricao as funcao, c.nome as colaborador, c.data_admissao, c.data_inatividade, b.carga_horaria, b.meses, b.quantidade, valor_inicial, (valor_inicial * meses) as valor_total
				from area_funcao a 
				left join funcao b on b.id = a.funcao_id 
				left join usuario c on c.id = a.colaborador_id 
				left join area d on d.id = a.area_id
				where a.del = 0  and c.permissao = 1 order by b.codigo asc ';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	public function getAreaFuncoesBolsista()
	{
		$sql = 'select a.*, b.codigo, d.descricao as area, b.descricao as funcao, c.nome as colaborador, c.data_admissao, c.data_inatividade, b.carga_horaria, b.meses, b.quantidade, valor_inicial, (valor_inicial * meses) as valor_total
				from area_funcao a 
				left join funcao b on b.id = a.funcao_id 
				left join usuario c on c.id = a.colaborador_id 
				left join area d on d.id = a.area_id
				where a.del = 0 and c.vinculo in (4,5,6,7,8,9,10,13,14,15,16,17,18,19,20,21,23,24,25,26)  order by b.codigo asc ';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	

	public function getAreaFuncoesDocente()
	{
		$sql = 'select a.*, b.codigo, d.descricao as area, b.descricao as funcao, c.nome as colaborador, c.data_admissao, c.data_inatividade, b.carga_horaria, b.meses, b.quantidade, valor_inicial, (valor_inicial * meses) as valor_total
				from area_funcao a 
				left join funcao b on b.id = a.funcao_id 
				left join usuario c on c.id = a.colaborador_id 
				left join area d on d.id = a.area_id
				where a.del = 0 and c.vinculo in (4,5,6,7,8,9,10)  order by b.codigo asc ';
		$statement = $this->tableGateway->adapter->query($sql);
		return $statement->execute();
	}

	
	public function getNucleosAtivosporArea($area_id)
	{
		$sql = 'select * from nucleo where del = 0 and area_id = ' . $area_id;

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getAreaFuncao($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function getAreaFuncaoPorColaborador($colaborador_id)
	{
		$colaborador_id  = (int) $colaborador_id;
		$rowset = $this->tableGateway->select(array('colaborador_id' => $colaborador_id));
		$row = $rowset->current();
		// if (!$row) {
		// 	throw new \Exception("Could not find row $colaborador_id");
		// }
		return $row;
	}

	public function saveAreaFuncao(AreaFuncao $area_funcao)
	{
		$data = array(
			'area_id' => $area_funcao->area_id,
			'funcao_id' => $area_funcao->funcao_id,
			'del' => $area_funcao->del						
		);

		$id = (int) $area_funcao->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getAreaFuncao($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('funcao id does not exist');
			}
		}
	}


	public function saveColaborador($colaborador_id, $funcao_id, $funcao_id2)
	{
		$data = array(
			'colaborador_id' => NULL
		);
		$this->tableGateway->update($data, array('id' => $funcao_id2));
		
		$data1 = array(
			'colaborador_id' => $colaborador_id			
		);

		$this->tableGateway->update($data1, array('id' => $funcao_id));
		
	}

	public function deleteAreaFuncao($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
