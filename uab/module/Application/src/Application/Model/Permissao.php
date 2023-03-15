<?php

namespace Application\Model;

class Permissao
{
	public $id;
	public $descricao;
	public $del;
	public $ordem;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
		$this->ordem     = (!empty($data['ordem'])) ? $data['ordem'] : null;	
	}
}