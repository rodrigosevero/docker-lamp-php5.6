<?php

namespace Application\Model;

class UsuarioAtividade
{
	public $id;
	public $projeto_id;
	public $usuario_id;
	public $disciplina;
	public $texto;
	public $data_inicial;
	public $data_final;
	public $data_abrang_ini;
	public $data_abrang_fim;
	public $data_lanc;
	public $hora_lanc;
	public $data_relatorio;
	public $assinatura_colaborador;
	public $data_assinatura_colaborador;
	public $assinatura_representante;
	public $data_assinatura_representante;
	public $ip;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->projeto_id     = (!empty($data['projeto_id'])) ? $data['projeto_id'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;	
		$this->disciplina     = (!empty($data['disciplina'])) ? $data['disciplina'] : null;	
		$this->texto     = (!empty($data['texto'])) ? $data['texto'] : null;	
		$this->data_inicial     = (!empty($data['data_inicial'])) ? $data['data_inicial'] : null;	
		$this->data_final     = (!empty($data['data_final'])) ? $data['data_final'] : null;	
		$this->data_abrang_ini     = (!empty($data['data_abrang_ini'])) ? $data['data_abrang_ini'] : null;	
		$this->data_abrang_fim     = (!empty($data['data_abrang_fim'])) ? $data['data_abrang_fim'] : null;	
		$this->data_lanc     = (!empty($data['data_lanc'])) ? $data['data_lanc'] : null;	
		$this->hora_lanc     = (!empty($data['hora_lanc'])) ? $data['hora_lanc'] : null;	
		$this->data_relatorio     = (!empty($data['data_relatorio'])) ? $data['data_relatorio'] : null;	
		$this->assinatura_colaborador     = (!empty($data['assinatura_colaborador'])) ? $data['assinatura_colaborador'] : null;	
		$this->data_assinatura_colaborador     = (!empty($data['data_assinatura_colaborador'])) ? $data['data_assinatura_colaborador'] : null;
		$this->assinatura_representante     = (!empty($data['assinatura_representante'])) ? $data['assinatura_representante'] : null;	
		$this->data_assinatura_representante     = (!empty($data['data_assinatura_representante'])) ? $data['data_assinatura_representante'] : null;	
		$this->ip     = (!empty($data['ip'])) ? $data['ip'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}