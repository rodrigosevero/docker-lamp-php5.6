<?php

namespace Application\Model;

class TipoProjeto
{
	public $id;
	public $descricao;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}