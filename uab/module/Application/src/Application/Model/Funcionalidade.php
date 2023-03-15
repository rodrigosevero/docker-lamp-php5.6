<?php

namespace Application\Model;

class Funcionalidade
{
	public $funcionalidade_id;
	public $funcionalidade_nome;
	public $funcionalidade_pai;

	public function exchangeArray($data)
	{
		$this->funcionalidade_id     = (!empty($data['funcionalidade_id'])) ? $data['funcionalidade_id'] : null;
		$this->funcionalidade_nome     = (!empty($data['funcionalidade_nome'])) ? $data['funcionalidade_nome'] : null;
		$this->funcionalidade_pai     = (!empty($data['funcionalidade_pai'])) ? $data['funcionalidade_pai'] : null;	
	}
}