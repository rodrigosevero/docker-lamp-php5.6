<?php

namespace Application\Model;

class CumprimentoObjetoAtividade
{
	public $id;
	public $area_id;
	public $atividade_epe_id;
	public $atividade_ptc_id;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->atividade_epe_id     = (!empty($data['atividade_epe_id'])) ? $data['atividade_epe_id'] : null;	
		$this->atividade_ptc_id = (!empty($data['atividade_ptc_id'])) ? $data['atividade_ptc_id'] : null;			
		$this->del = (!empty($data['del'])) ? $data['del'] : null;		
	}
}