<?php

namespace Application\Model;

class Escolaridade
{
	public $id;
	public $descricao;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;

	}
}