<?php

namespace Application\Model;

class AtividadePTCProjeto
{
	public $id;
	public $area_id;
	public $atividade_projeto;
	public $atividade_ptc_id;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->atividade_projeto     = (!empty($data['atividade_projeto'])) ? $data['atividade_projeto'] : null;
		$this->atividade_ptc_id     = (!empty($data['atividade_ptc_id'])) ? $data['atividade_ptc_id'] : null;
		$this->del     = (!empty($data['del'])) ? $data['del'] : null;		
	}
}