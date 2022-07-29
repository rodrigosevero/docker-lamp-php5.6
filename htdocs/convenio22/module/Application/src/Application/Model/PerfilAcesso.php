<?php

namespace Application\Model;

class PerfilAcesso
{
	public $perfil_acesso_id;
	public $permissao_id;
	public $funcionalidade_id;

	public function exchangeArray($data)
	{
		$this->perfil_acesso_id     = (!empty($data['perfil_acesso_id'])) ? $data['perfil_acesso_id'] : null;
		$this->permissao_id     = (!empty($data['permissao_id'])) ? $data['permissao_id'] : null;
		$this->funcionalidade_id     = (!empty($data['funcionalidade_id'])) ? $data['funcionalidade_id'] : null;	
	}
}