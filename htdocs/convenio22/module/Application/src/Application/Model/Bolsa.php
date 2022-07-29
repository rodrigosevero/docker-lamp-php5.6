<?php

namespace Application\Model;

class Bolsa
{
	public $id;	
	public $descricao;
	public $codigo;
	public $carga_horaria;
	public $meses;
	public $valor_inicial;
	public $valor_medio;
	public $valor_final;
	public $quantidade;
	public $requisitos;	
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		$this->codigo     = (!empty($data['codigo'])) ? $data['codigo'] : null;
		$this->carga_horaria     = (!empty($data['carga_horaria'])) ? $data['carga_horaria'] : null;	
		$this->meses     = (!empty($data['meses'])) ? $data['meses'] : null;	
		$this->valor_inicial     = (!empty($data['valor_inicial'])) ? $data['valor_inicial'] : null;	
		$this->valor_medio     = (!empty($data['valor_medio'])) ? $data['valor_medio'] : null;	
		$this->valor_final     = (!empty($data['valor_final'])) ? $data['valor_final'] : null;	
		$this->quantidade     = (!empty($data['quantidade'])) ? $data['quantidade'] : null;	
		$this->requisitos     = (!empty($data['requisitos'])) ? $data['requisitos'] : null;			
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}