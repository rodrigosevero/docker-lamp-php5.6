<?php

namespace Application\Model;

class Artefato
{
	public $id;
	public $arquivo;
	public $atividade_id;
	public $tipo;
	public $descricao;
	public $legenda;
	public $data;
	public $local;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->arquivo     = (!empty($data['arquivo'])) ? $data['arquivo'] : null;	
		$this->atividade_id     = (!empty($data['atividade_id'])) ? $data['atividade_id'] : null;	
		$this->tipo     = (!empty($data['tipo'])) ? $data['tipo'] : null;	
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;	
		$this->legenda     = (!empty($data['legenda'])) ? $data['legenda'] : null;	
		$this->data     = (!empty($data['data'])) ? $data['data'] : null;	
		$this->local     = (!empty($data['local'])) ? $data['local'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}