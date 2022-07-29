<?php

namespace Application\Model;

class RepresentanteUsuario
{
	public $id;
	public $usuario_id;
	public $representante_id;
	public $data;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
		$this->representante_id     = (!empty($data['representante_id'])) ? $data['representante_id'] : null;	
		$this->data = (!empty($data['data'])) ? $data['data'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}