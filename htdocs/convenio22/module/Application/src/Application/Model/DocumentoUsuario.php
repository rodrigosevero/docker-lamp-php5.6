<?php

namespace Application\Model;

class DocumentoUsuario
{
	public $id;
	public $tipo;
	public $arquivo;
	public $usuario_id;
	public $usuario_upload_id;
	public $data;
	public $hora;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->tipo     = (!empty($data['tipo'])) ? $data['tipo'] : null;
		$this->arquivo     = (!empty($data['arquivo'])) ? $data['arquivo'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;	
		$this->usuario_upload_id = (!empty($data['usuario_upload_id'])) ? $data['usuario_upload_id'] : null;			
		$this->data = (!empty($data['data'])) ? $data['data'] : null;			
		$this->hora = (!empty($data['hora'])) ? $data['hora'] : null;			
	}
}