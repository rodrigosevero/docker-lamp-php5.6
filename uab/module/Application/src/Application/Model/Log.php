<?php

namespace Application\Model;

class Log
{
	public $log_id;
	public $usuario_id;
	public $log_data;
	public $log_acao;
	public $log_acao_id;
	public $log_acao_exibicao;

	public function exchangeArray($data)
	{
		$this->log_id     = (!empty($data['log_id'])) ? $data['log_id'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
		$this->log_data     = (!empty($data['log_data'])) ? $data['log_data'] : null;
		$this->log_acao     = (!empty($data['log_acao'])) ? $data['log_acao'] : null;
		$this->log_acao_id     = (!empty($data['log_acao_id'])) ? $data['log_acao_id'] : null;
		$this->log_acao_exibicao     = (!empty($data['log_acao_exibicao'])) ? $data['log_acao_exibicao'] : null;
	}
}