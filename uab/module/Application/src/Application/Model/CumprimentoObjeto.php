<?php

namespace Application\Model;

class CumprimentoObjeto
{
	public $id;
	public $atividade_id;
	public $parcial_total;
	public $data;
	public $atividade_1;
	public $atividade_1_justifique;
	public $atividade_2;
	public $atividade_2_justifique;
	public $acoes_executadas;
	public $avaliacao;
	public $quantitativos_executados;
	public $quantitativos_executados_justifique;
	public $principais_resultados;
	public $restricoes;
	public $usuario_id;
	public $data_alteracao;
	public $hora_alteracao;
	public $resumo_relatorio;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->atividade_id     = (!empty($data['atividade_id'])) ? $data['atividade_id'] : null;
		$this->parcial_total     = (!empty($data['parcial_total'])) ? $data['parcial_total'] : null;	
		$this->data = (!empty($data['data'])) ? $data['data'] : null;			
		$this->atividade_1     = (!empty($data['atividade_1'])) ? $data['atividade_1'] : null;
		$this->atividade_1_justifique     = (!empty($data['atividade_1_justifique'])) ? $data['atividade_1_justifique'] : null;	
		$this->atividade_2 = (!empty($data['atividade_2'])) ? $data['atividade_2'] : null;			
		$this->atividade_2_justifique     = (!empty($data['atividade_2_justifique'])) ? $data['atividade_2_justifique'] : null;
		$this->acoes_executadas     = (!empty($data['acoes_executadas'])) ? $data['acoes_executadas'] : null;	
		$this->avaliacao = (!empty($data['avaliacao'])) ? $data['avaliacao'] : null;			
		$this->quantitativos_executados     = (!empty($data['quantitativos_executados'])) ? $data['quantitativos_executados'] : null;
		$this->quantitativos_executados_justifique     = (!empty($data['quantitativos_executados_justifique'])) ? $data['quantitativos_executados_justifique'] : null;	
		$this->principais_resultados = (!empty($data['principais_resultados'])) ? $data['principais_resultados'] : null;			
		$this->restricoes     = (!empty($data['restricoes'])) ? $data['restricoes'] : null;
		$this->usuario_id     = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;	
		$this->data_alteracao = (!empty($data['data_alteracao'])) ? $data['data_alteracao'] : null;			
		$this->hora_alteracao     = (!empty($data['hora_alteracao'])) ? $data['hora_alteracao'] : null;
		$this->resumo_relatorio     = (!empty($data['resumo_relatorio'])) ? $data['resumo_relatorio'] : null;	
		$this->del = (!empty($data['del'])) ? $data['del'] : null;		
	}
}