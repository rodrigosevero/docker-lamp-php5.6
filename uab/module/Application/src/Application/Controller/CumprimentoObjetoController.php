<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

use Application\Model\CumprimentoObjetoTable;
use Application\Model\CumprimentoObjeto;

use Application\Model\CumprimentoObjetoAtividadeTable;
use Application\Model\CumprimentoObjetoAtividade;

use Application\Model\AtividadePTCTable;
use Application\Model\AtividadePTC;

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\AreaTable;
use Application\Model\Area;

use Application\Model\Artefato;
use Application\Model\ArtefatoTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\AtividadeProjetoTable;
use Application\Model\AtividadeProjeto;

use Application\Model\LogTable;
use Application\Model\Log;

class CumprimentoObjetoController extends AbstractActionController
{	    	    
    protected $cumprimentoObjetoTable;
    protected $cumprimentoObjetoAtividadeTable;
    protected $atividadePTCTable;	   
    protected $submetaTable;
    protected $areaTable;	
    protected $artefatoTable;
    protected $projetoTable;
    protected $atividadeProjetoTable;  
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}			
    
	public function getAtividadeProjetoTable()
	{
		if (!$this->atividadeProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->atividadeProjetoTable = $sm->get('Application\Model\AtividadeProjetoTable');
		}
		return $this->atividadeProjetoTable;
	}	
    
	public function getProjetoTable()
	{
		if (!$this->projetoTable) {
			$sm = $this->getServiceLocator();
			$this->projetoTable = $sm->get('Application\Model\ProjetoTable');
		}
		return $this->projetoTable;
	}	
    
	public function getArtefatoTable()
	{
		if (!$this->artefatoTable) {
			$sm = $this->getServiceLocator();
			$this->artefatoTable = $sm->get('Application\Model\ArtefatoTable');
		}
		return $this->artefatoTable;
	}	
    
	public function getAreaTable()
	{
		if (!$this->areaTable) {
			$sm = $this->getServiceLocator();
			$this->areaTable = $sm->get('Application\Model\AreaTable');
		}
		return $this->areaTable;
	}	    	
        
	public function getSubmetaTable()
	{
		if (!$this->submetaTable) {
			$sm = $this->getServiceLocator();
			$this->submetaTable = $sm->get('Application\Model\SubmetaTable');
		}
		return $this->submetaTable;
	}	
    
	public function getAtividadePTCTable()
	{
		if (!$this->atividadePTCTable) {
			$sm = $this->getServiceLocator();
			$this->atividadePTCTable = $sm->get('Application\Model\AtividadePTCTable');
		}
		return $this->atividadePTCTable;
	}	
        
	public function getCumprimentoObjetoTable()
	{
		if (!$this->cumprimentoObjetoTable) {
			$sm = $this->getServiceLocator();
			$this->cumprimentoObjetoTable = $sm->get('Application\Model\CumprimentoObjetoTable');
		}
		return $this->cumprimentoObjetoTable;
	}	
        
	public function getCumprimentoObjetoAtividadeTable()
	{
		if (!$this->cumprimentoObjetoAtividadeTable) {
			$sm = $this->getServiceLocator();
			$this->cumprimentoObjetoAtividadeTable = $sm->get('Application\Model\CumprimentoObjetoAtividadeTable');
		}
		return $this->cumprimentoObjetoAtividadeTable;
	}	
				
	public function addAction()
	{	
		/*
		$artefatos = $this->getArtefatoTable()->fetchAll();
		
		foreach ($artefatos as $artefato){
		
			$artefato->descricao = utf8_decode($artefato->descricao);
			$artefato->legenda = utf8_decode($artefato->legenda);
			//$artefato->legenda = utf8_encode($artefato->legenda);
			$artefato->tipo = utf8_decode($artefato->tipo);
				
				
			$this->getArtefatoTable()->saveArtefato($artefato);
		}
		die;
		
		
		 $atividades_ptc = $this->getCumprimentoObjetoTable()->getCumprimentoObjetosDESC();
		
		 foreach ($atividades_ptc as $cumprimento_objeto){
		 	$cumprimento_objeto_bd = new CumprimentoObjeto();
		 				 		 	
		 	$cumprimento_objeto_bd->id = $cumprimento_objeto['id'];
		 	$cumprimento_objeto_bd->atividade_id = $cumprimento_objeto['atividade_id'];
		 	$cumprimento_objeto_bd->parcial_total = $cumprimento_objeto['parcial_total'];
		 	$cumprimento_objeto_bd->data = $cumprimento_objeto['data'];
		 	$cumprimento_objeto_bd->atividade_1 = $cumprimento_objeto['atividade_1'];
		 	$cumprimento_objeto_bd->atividade_1_justifique = utf8_decode($cumprimento_objeto['atividade_1_justifique']);
		 	$cumprimento_objeto_bd->atividade_2 = $cumprimento_objeto['atividade_2'];
		 	$cumprimento_objeto_bd->atividade_2_justifique = utf8_decode($cumprimento_objeto['atividade_2_justifique']);
		 	//$cumprimento_objeto_bd->atividade_2_justifique = utf8_encode($cumprimento_objeto['atividade_2_justifique']);
		 	$cumprimento_objeto_bd->acoes_executadas = $cumprimento_objeto['acoes_executadas'];
		 	$cumprimento_objeto_bd->acoes_executadas = utf8_decode($cumprimento_objeto['acoes_executadas']);
		 	$cumprimento_objeto_bd->avaliacao = utf8_decode($cumprimento_objeto['avaliacao']);
		 	$cumprimento_objeto_bd->quantitativos_executados = $cumprimento_objeto['quantitativos_executados'];
		 	$cumprimento_objeto_bd->quantitativos_executados_justifique = utf8_decode($cumprimento_objeto['quantitativos_executados_justifique']);
		 	$cumprimento_objeto_bd->principais_resultados = utf8_decode($cumprimento_objeto['principais_resultados']);
		 	$cumprimento_objeto_bd->restricoes = utf8_decode($cumprimento_objeto['restricoes']);
		 	$cumprimento_objeto_bd->usuario_id = $cumprimento_objeto['usuario_id'];
		 	$cumprimento_objeto_bd->data_alteracao = $cumprimento_objeto['data_alteracao'];
		 	$cumprimento_objeto_bd->hora_alteracao = $cumprimento_objeto['hora_alteracao'];
		 	//$cumprimento_objeto_bd->resumo_relatorio = utf8_decode($cumprimento_objeto['resumo_relatorio']);
		 	$cumprimento_objeto_bd->del = $cumprimento_objeto['del'];
		 	 		
		 	$this->getCumprimentoObjetoTable()->saveCumprimentoObjeto($cumprimento_objeto_bd);
		 }
		 die;*/
		 
		
    	$atividade_id = (int) $this->params()->fromRoute('atividade_id', 0);
    	$request = $this->getRequest();

    	if (!$atividade_id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}

    	try {
    		$atividade_ptc = $this->getAtividadePTCTable()->getAtividadePTC($atividade_id);

    		$area = $this->getAreaTable()->getArea($atividade_ptc->area_id);
    		$submeta = $this->getSubmetaTable()->getSubmeta($atividade_ptc->submeta_id);
    		
    		$ultimo_cumprimento_objeto_by_area = $this->getCumprimentoObjetoTable()->getLastCumprimentoObjetoByAtividade($atividade_id);
    	
    		$artefatos = $this->getArtefatoTable()->getArtefatosAtivosByArea($atividade_id);
    		
    		$cumprimento_objeto_atividades = $this->getCumprimentoObjetoAtividadeTable()->getCumprimentoObjetoAtividadesAtivosByAtividadePTC($atividade_id);

    		$projetos_ativos = $this->getProjetoTable()->getProjetos();
    		
    		$projetos_bd = $this->getProjetoTable()->getProjetosByAtividadePTC($atividade_id);
    		$projetos = array();
    		 
    		foreach ($projetos_bd as $projeto){
    			$projetos[$projeto['projeto_id']] = array('descricao' => $projeto['projeto'], 'meta' => $projeto['meta']);
    		}
    		
	    	$atividades_projeto_bd = $this->getAtividadeProjetoTable()->fetchAll();
	    	$atividades_projeto = array();
	    	 
	    	foreach ($atividades_projeto_bd as $atividade_projeto){
	    		$atividades_projeto[$atividade_projeto->id] = $atividade_projeto->descricao;
	    	}
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		$cumprimento_objeto = new CumprimentoObjeto();
    		$dados_sessao_atual = new Container('usuario_dados');
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			$cumprimento_objeto->atividade_id = $atividade_id;
    			$cumprimento_objeto->parcial_total = $dados_form['parcial_total'];
    			$cumprimento_objeto->data = implode("-",array_reverse(explode("/",$dados_form['data_geracao'])));
    			$cumprimento_objeto->atividade_1 = $dados_form['atividade_1'];
    			$cumprimento_objeto->atividade_1_justifique = addslashes(nl2br($dados_form['atividade_1_justifique']));
    			$cumprimento_objeto->atividade_2 = addslashes($dados_form['atividade_2']);
    			$cumprimento_objeto->atividade_2_justifique = addslashes(nl2br($dados_form['atividade_2_justifique']));
    			$cumprimento_objeto->acoes_executadas = addslashes(nl2br($dados_form['acoes_executadas']));
    			$cumprimento_objeto->avaliacao = addslashes(nl2br($dados_form['avaliacao']));
    			
    			$cumprimento_objeto->quantitativos_executados = $dados_form['quantitativos_executados'];
    			$cumprimento_objeto->quantitativos_executados_justifique = addslashes(nl2br($dados_form['quantitativos_executados_justifique']));
    			$cumprimento_objeto->principais_resultados = addslashes(nl2br($dados_form['principais_resultados']));
    			$cumprimento_objeto->restricoes = addslashes(nl2br($dados_form['restricoes']));
    			$cumprimento_objeto->data_alteracao = date('Y-m-d');
    			$cumprimento_objeto->hora_alteracao = date("H:i:s");   
    			$cumprimento_objeto->resumo_relatorio = addslashes($dados_form['resumo_relatorio']);
    			$cumprimento_objeto->usuario_id = $dados_sessao_atual->id;
    			$cumprimento_objeto->del = 0;
    			
    			$cumprimento_objeto_id = $this->getCumprimentoObjetoTable()->saveCumprimentoObjeto($cumprimento_objeto);
    			
    			/* INICIO Grava log */
    			$log_acao = "cumprimento-objeto/add";
    			$log_acao_id = $cumprimento_objeto_id;
    			$log_acao_exibicao = 'Cadastra cumprimento de objeto';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('cumprimento-objeto/add', array(
    					'action' => 'index', 'atividade_id' => $atividade_id
    			));
    		}
    	}
    	return new ViewModel(array(
    			'atividade_id' => $atividade_id,
         	 	'atividade_ptc' => $atividade_ptc,
    			'last_cumprimento_objeto' => $ultimo_cumprimento_objeto_by_area,
    			'area' => $area,
    			'submeta' => $submeta,
    			'artefatos' => $artefatos,
    			'projetos' => $projetos,
    			'projetos_ativos' => $projetos_ativos,
    			'cumprimento_objeto_atividades' => $cumprimento_objeto_atividades,
    			'atividades_projeto' => $atividades_projeto
    	));
	}
	
	public function editAction()
	{		 
    	$atividade_id = (int) $this->params()->fromRoute('atividade_id', 0);
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$request = $this->getRequest();

    	if (!$atividade_id) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	if (!$id) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	try {
    		$area = $this->getAreaTable()->getArea($atividade_id);
    		$cumprimento_objeto = $this->getCumprimentoObjetoTable()->getCumprimentoObjeto($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		//$cumprimento_objeto = new CumprimentoObjeto();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			$cumprimento_objeto->descricao = $dados_form['descricao'];
    			
    			$this->getCumprimentoObjetoTable()->saveCumprimentoObjeto($cumprimento_objeto);
    			
    			return $this->redirect()->toRoute('cumprimento_objeto/consulta', array(
    					'action' => 'consulta', 'atividade_id' => $atividade_id
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
    			'id' => $id,
    			'atividade_id' => $atividade_id,
         	 	'area' => $area,
    			'cumprimento_objeto' => $cumprimento_objeto
    	));
	}
	
	public function salvarLog($acao, $acao_id, $acao_exibicao)
	{
		$session_dados = new Container('usuario_dados');
		 
		$log = new Log();
		date_default_timezone_set('America/Cuiaba');
		$log->usuario_id = $session_dados->id;
		$log->log_data = date('Y-m-d H:i:s');
		$log->log_acao = $acao;
		$log->log_acao_id = $acao_id;
		$log->log_acao_exibicao = $acao_exibicao;
	
		$this->getLogTable()->saveLog($log);
	}

}
