<?php

namespace Application\Model;

class Submeta
{
	public $id;
	public $area_id;
	public $codigo;
	public $descricao;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->codigo     = (!empty($data['codigo'])) ? $data['codigo'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}