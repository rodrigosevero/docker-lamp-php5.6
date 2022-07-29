<?php

namespace Application\Model;

class UsuarioPermissao
{
	public $id;	
	public $usuario_id;
	public $permissao_id;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;	
		$this->permissao_id     = (!empty($data['permissao_id'])) ? $data['permissao_id'] : null;			
	}
}