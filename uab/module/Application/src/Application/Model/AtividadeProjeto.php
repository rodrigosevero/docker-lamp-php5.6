<?php

namespace Application\Model;

class AtividadeProjeto
{
	public $id;
	public $projeto_id;
	public $dt_inicial;
	public $dt_final;
	public $ptc;
	public $descricao;
	public $prev_inicio;
	public $prev_fim;
	public $prazo;
	public $epe;
	//public $inativo;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->projeto_id     = (!empty($data['projeto_id'])) ? $data['projeto_id'] : null;
		$this->dt_inicial     = (!empty($data['dt_inicial'])) ? $data['dt_inicial'] : null;
		$this->dt_final     = (!empty($data['dt_final'])) ? $data['dt_final'] : null;	
		$this->ptc     = (!empty($data['ptc'])) ? $data['ptc'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;	
		$this->prev_inicio     = (!empty($data['prev_inicio'])) ? $data['prev_inicio'] : null;
		$this->prev_fim     = (!empty($data['prev_fim'])) ? $data['prev_fim'] : null;	
		$this->prazo     = (!empty($data['prazo'])) ? $data['prazo'] : null;
		$this->epe     = (!empty($data['epe'])) ? $data['epe'] : null;	
	//	$this->inativo     = (!empty($data['inativo'])) ? $data['inativo'] : null;
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}