<?php

namespace Application\Model;

class Nucleo
{
	public $id;
	public $area_id;
	public $descricao;
	public $coordenador_id;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->coordenador_id     = (!empty($data['coordenador_id'])) ? $data['coordenador_id'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}