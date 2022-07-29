<?php

namespace Application\Model;

class AtividadePTC
{
	public $id;
	public $area_id;
	public $submeta_id;
	public $etapa;
	public $atividade;
	public $inicio;
	public $fim;
	public $indicador_unid;
	public $indicador_quant;
	public $porcentagem_realizacao;
	public $indicador_desempenho;
	public $resultados_esperados;
	public $produtos_esperadoos;
	public $fluxo_continuo;
	public $data_inicio;
	public $data_fim;
	public $relatorio;
	public $resultados;
	public $produtos;
	public $status;
    public $nucleo_id;
	public $del;

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->area_id     = (!empty($data['area_id'])) ? $data['area_id'] : null;
		$this->submeta_id     = (!empty($data['submeta_id'])) ? $data['submeta_id'] : null;
		$this->etapa     = (!empty($data['etapa'])) ? $data['etapa'] : null;	
		$this->atividade     = (!empty($data['atividade'])) ? $data['atividade'] : null;
		$this->inicio     = (!empty($data['inicio'])) ? $data['inicio'] : null;	
		$this->fim     = (!empty($data['fim'])) ? $data['fim'] : null;
		$this->indicador_unid     = (!empty($data['indicador_unid'])) ? $data['indicador_unid'] : null;	
		$this->indicador_quant     = (!empty($data['indicador_quant'])) ? $data['indicador_quant'] : null;
		$this->porcentagem_realizacao = (!empty($data['porcentagem_realizacao'])) ? $data['porcentagem_realizacao'] : null;
		$this->resultados_esperados = (!empty($data['resultados_esperados'])) ? $data['resultados_esperados'] : null;		
		$this->produtos_esperados = (!empty($data['produtos_esperados'])) ? $data['produtos_esperados'] : null;				
		$this->indicador_desempenho     = (!empty($data['indicador_desempenho'])) ? $data['indicador_desempenho'] : null;	
		$this->fluxo_continuo     = (!empty($data['fluxo_continuo'])) ? $data['fluxo_continuo'] : null;
		$this->data_inicio = (!empty($data['data_inicio'])) ? $data['data_inicio'] : null;			
		$this->data_fim     = (!empty($data['data_fim'])) ? $data['data_fim'] : null;
		$this->relatorio     = (!empty($data['relatorio'])) ? $data['relatorio'] : null;	
		$this->resultados     = (!empty($data['resultados'])) ? $data['resultados'] : null;	
		$this->produtos     = (!empty($data['produtos'])) ? $data['produtos'] : null;	
		$this->status     = (!empty($data['status'])) ? $data['status'] : null;
        $this->nucleo_id     = (!empty($data['nucleo_id'])) ? $data['nucleo_id'] : null;
		$this->del     = (!empty($data['del'])) ? $data['del'] : null;

	}
}