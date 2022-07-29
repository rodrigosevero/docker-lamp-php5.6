<?php

namespace Application\Model;

class AtividadeProjetoUsuario
{
	public $id;
	public $atividade_projeto_id;
	public $projeto_id;
	public $usuario_id;
	public $data;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->atividade_projeto_id = (!empty($data['atividade_projeto_id'])) ? $data['atividade_projeto_id'] : null;
		$this->projeto_id     = (!empty($data['projeto_id'])) ? $data['projeto_id'] : null;
		$this->usuario_id = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
		$this->data     = (!empty($data['data'])) ? $data['data'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}