<?php

namespace Application\Model;

class RepresentanteProjeto
{
	public $id;
	public $representante_id;
	public $projeto_id;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->representante_id     = (!empty($data['representante_id'])) ? $data['representante_id'] : null;
		$this->projeto_id     = (!empty($data['projeto_id'])) ? $data['projeto_id'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}