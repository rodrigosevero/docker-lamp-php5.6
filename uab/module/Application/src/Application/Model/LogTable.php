<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class LogTable
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

	public function getUsuarioLogs($usuario_id)
	{
		$resultSet = $this->tableGateway->select(array('usuario_id' => $usuario_id));
		return $resultSet;
	}
		
	public function saveLog(Log $log)
	{
		$data = array(
				'log_id' => $log->log_id,
				'usuario_id' => $log->usuario_id,
				'log_data' => $log->log_data,
				'log_acao' => $log->log_acao,
				'log_acao_id' => $log->log_acao_id,
				'log_acao_exibicao' => $log->log_acao_exibicao
		);

		$id = (int) $log->log_id;
		
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getUsuario($id)) {
				$this->tableGateway->update($data, array('log_id' => $id));
			} else {
				throw new \Exception('Usuario id does not exist');
			}
		}
	}
}