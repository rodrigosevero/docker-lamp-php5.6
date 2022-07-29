<?php

namespace Application\Model;

class Projeto
{
	public $id;
	
	public $pai;
	public $coordenador_tce_mpc;
	public $data_inicio;
	public $data_fim;
	public $carga_horaria;
	public $vagas_ofertadas;
	public $entidade_certificadora;
	public $status;
	
	public $descricao;
	public $tipo_projeto;
	public $area_id;
	public $nucleo_id;
	public $coordenador_id;
	public $representante_tce_id;
	public $usuario_id;
	public $data;
	public $hora;
	public $inativo;
	public $arquivo;
	public $projeto_especial;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->descricao     = (!empty($data['descricao'])) ? $data['descricao'] : null;
		
		$this->pai  = (!empty($data['pai'])) ? $data['pai'] : null;
		$this->coordenador_tce_mpc  = (!empty($data['coordenador_tce_mpc'])) ? $data['coordenador_tce_mpc'] : null;		
		$this->data_inicio  = (!empty($data['data_inicio'])) ? $data['data_inicio'] : null;		
		$this->data_fim  = (!empty($data['data_fim'])) ? $data['data_fim'] : null;		
		$this->carga_horaria = (!empty($data['carga_horaria'])) ? $data['carga_horaria'] : null;		
		$this->vagas_ofertadas = (!empty($data['vagas_ofertadas'])) ? $data['vagas_ofertadas'] : null;		
		$this->entidade_certificadora = (!empty($data['entidade_certificadora'])) ? $data['entidade_certificadora'] : null;		
		$this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->arquivo = (!empty($data['arquivo'])) ? $data['arquivo'] : null;
        $this->projeto_especial = (!empty($data['projeto_especial'])) ? $data['projeto_especial'] : null;		
		$this->tipo_projeto     = (!empty($data['tipo_projeto'])) ? $data['tipo_projeto'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->nucleo_id     = (!empty($data['nucleo_id'])) ? $data['nucleo_id'] : null;
		$this->coordenador_id     = (!empty($data['coordenador_id'])) ? $data['coordenador_id'] : null;
		$this->representante_tce_id     = (!empty($data['representante_tce_id'])) ? $data['representante_tce_id'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
		$this->data     = (!empty($data['data'])) ? $data['data'] : null;
		$this->hora     = (!empty($data['hora'])) ? $data['hora'] : null;
		$this->inativo     = (!empty($data['inativo'])) ? $data['inativo'] : null;
		$this->del = (!empty($data['del'])) ? $data['del'] : null;			
	}
}