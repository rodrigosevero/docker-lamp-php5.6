<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class UsuarioAtividadeTable
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

	public function getUsuariosAtividadesAtivas()
	{
		$sql = 'select a.* from usuario_atividade a where a.del = 0';

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuarioAtividadesAtivasByProjetoByUsuario($projeto_id, $usuario_id)
	{
		$sql = "SELECT *, data_final, DATE_FORMAT(data_final,'%Y') as ano, DATE_FORMAT(data_final,'%m') as mes FROM usuario_atividade where del = 0 and projeto_id = $projeto_id and usuario_id = $usuario_id GROUP BY YEAR(data_final), MONTH(data_final) ORDER BY id desc, ano DESC, mes desc";

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuarioLastAtividades()
	{
		$sql = "select * from usuario_atividade where del = 0 order by id desc";

		$statement = $this->tableGateway->adapter->query($sql);

		return $statement->execute();
	}

	public function getUsuarioAtividadeByProjetoByUsuarioByData($projeto_id, $usuario_id, $mes, $ano)
	{
		$sql = "select * from usuario_atividade where projeto_id = $projeto_id AND MONTH(data_final) = $mes AND YEAR(data_final) = $ano and usuario_id = $usuario_id order by id asc limit 1";

		$statement = $this->tableGateway->adapter->query($sql);
		$row = $statement->execute();

		return $row->current();
	}

	public function getUsuarioAtividade($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}

	public function saveUsuarioAtividade(UsuarioAtividade $usuario_atividade)
	{

		$data = array(
			'projeto_id' => $usuario_atividade->projeto_id,
			'usuario_id' => $usuario_atividade->usuario_id,
			'disciplina' => $usuario_atividade->disciplina,
			'texto' => $usuario_atividade->texto,
			'data_inicial' => date('Y-m-d', strtotime($usuario_atividade->data_inicial)),
			'data_final' => date('Y-m-d', strtotime($usuario_atividade->data_final)),
			'data_abrang_ini' => date('Y-m-d', strtotime($usuario_atividade->data_abrang_ini)),
			'data_abrang_fim' => date('Y-m-d', strtotime($usuario_atividade->data_abrang_fim)),
			'data_lanc' => date('Y-m-d', strtotime($usuario_atividade->data_lanc)),
			'hora_lanc' => $usuario_atividade->hora_lanc,
			'data_relatorio' => date('Y-m-d', strtotime($usuario_atividade->data_relatorio)),
			'assinatura_colaborador' => $usuario_atividade->assinatura_colaborador,
			'data_assinatura_colaborador' => $usuario_atividade->data_assinatura_colaborador,
			'assinatura_representante' => $usuario_atividade->assinatura_representante,
			'data_assinatura_representante' => $usuario_atividade->data_assinatura_representante,
			'ip' => $usuario_atividade->ip,
			'del' => $usuario_atividade->del,
		);

		$id = (int) $usuario_atividade->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			return $id;
		} else {
			if ($this->getUsuarioAtividade($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('usuario_atividade id does not exist');
			}
		}
	}


	public function assinarRelatorioColaborador(UsuarioAtividade $usuario_atividade)
	{

		$data = array(
			'assinatura_colaborador' => $usuario_atividade->assinatura_colaborador,
			'data_assinatura_colaborador' => $usuario_atividade->data_assinatura_colaborador,
		);

		$id = (int) $usuario_atividade->id;
		if ($this->getUsuarioAtividade($id)) {
			$this->tableGateway->update($data, array('id' => $id));
		} else {
			throw new \Exception('usuario_atividade id does not exist');
		}
	}

	public function assinarRelatorioRepresentante(UsuarioAtividade $usuario_atividade)
	{

		$data = array(
			'assinatura_representante' => $usuario_atividade->assinatura_representante,
			'data_assinatura_representante' => $usuario_atividade->data_assinatura_representante,
		);

		$id = (int) $usuario_atividade->id;
		if ($this->getUsuarioAtividade($id)) {
			$this->tableGateway->update($data, array('id' => $id));
		} else {
			throw new \Exception('usuario_atividade id does not exist');
		}
	}



	public function deleteUsuarioAtividade($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
