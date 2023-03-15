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
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Model\TestEntity;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

use Application\Model\AtividadePTCProjetoTable;
use Application\Model\AtividadePTCProjeto;

use Application\Model\AtividadePTCTable;
use Application\Model\AtividadePTC;

use Application\Model\AtividadeProjetoTable;
use Application\Model\AtividadeProjeto;

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\ProjetoTable;
use Application\Model\Projeto;
use Zend\View\Model\JsonModel;

use Application\Model\LogTable;
use Application\Model\Log;

class AtividadePTCController extends AbstractActionController
{	    	    
    protected $atividadePTCTable;
    protected $areaTable;	
    protected $nucleoTable;	
    protected $submetaTable;
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}			

    public function getNucleoTable()
    {
    	if (!$this->nucleoTable) {
    		$sm = $this->getServiceLocator();
    		$this->nucleoTable = $sm->get('Application\Model\NucleoTable');
    	}
    	return $this->nucleoTable;
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

	public function concluidoAction()
	{
    	try {

    		$areas_bd = $this->getAreaTable()->fetchAll();
    		$areas = array();
	    	foreach ($areas_bd as $area){
	    		$areas[$area->id] = $area->descricao;
	    	}
	    	
	    	$nucleos_bd = $this->getNucleoTable()->fetchAll();
	    	$nucleos = array();	    	
	    	foreach ($nucleos_bd as $nucleo){
	    		$nucleos[$nucleo->id] = $nucleo->descricao;
	    	}	

    		$submetas_bd = $this->getSubmetaTable()->fetchAll();
    		$submetas = array();
	    	foreach ($submetas_bd as $submeta){
	    		$submetas[$submeta->id] = array('descricao' => $submeta->descricao, 'codigo' => $submeta->codigo);
	    	}	

	    	$atividades_ptc = $this->getAtividadePTCTable()->getAtividadesPTCAtivas();
	    	$atividades_concluidas = $this->getAtividadePTCTable()->getAtividadesPTCConcluidas();
	    	
		}catch (\Exception $ex) {
			return $this->redirect()->toRoute('home', array(
					'action' => 'index'
			));
		}
		
    	/* INICIO Grava log */
    	$log_acao = "atividade-ptc";
    	$log_acao_id = NULL;
    	$log_acao_exibicao = 'Consulta atividades concluídas de plano de trabalho ';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
	
		return new ViewModel(array(
				'atividades_ptc' => $atividades_ptc,
				'nucleos' => $nucleos,
				'areas' => $areas,
				'submetas' => $submetas,
				'atividades_concluidas' => $atividades_concluidas
		));
	}
	
	public function indexAction()
	{			
	/*
		$atividades_ptc = $this->getAtividadePTCTable()->fetchAll();
		
		foreach ($atividades_ptc as $atividade_ptc){	
			$atividade_ptc->etapa = utf8_encode($atividade_ptc->etapa);
			$atividade_ptc->etapa = utf8_decode($atividade_ptc->etapa);
			$atividade_ptc->atividade = utf8_decode($atividade_ptc->atividade);
			$atividade_ptc->indicador_unid = utf8_encode($atividade_ptc->indicador_unid);
			$atividade_ptc->indicador_unid = utf8_decode($atividade_ptc->indicador_unid);
			$atividade_ptc->indicador_quant = utf8_decode($atividade_ptc->indicador_quant);
			$atividade_ptc->indicador_desempenho = utf8_encode($atividade_ptc->indicador_desempenho);
			$atividade_ptc->indicador_desempenho = utf8_decode($atividade_ptc->indicador_desempenho);
			$atividade_ptc->relatorio = utf8_encode($atividade_ptc->relatorio);
			$atividade_ptc->relatorio = utf8_decode($atividade_ptc->relatorio);


			$this->getAtividadePTCTable()->saveAtividadePTC($atividade_ptc);
		}
		die;
		*/
    	try {

    		$areas_bd = $this->getAreaTable()->fetchAll();
    		$areas = array();
	    	foreach ($areas_bd as $area){
	    		$areas[$area->id] = $area->descricao;
	    	}
	    	
	    	$nucleos_bd = $this->getNucleoTable()->fetchAll();
	    	$nucleos = array();	    	
	    	foreach ($nucleos_bd as $nucleo){
	    		$nucleos[$nucleo->id] = $nucleo->descricao;
	    	}	

    		$submetas_bd = $this->getSubmetaTable()->fetchAll();
    		$submetas = array();
	    	foreach ($submetas_bd as $submeta){
	    		$submetas[$submeta->id] = array('descricao' => $submeta->descricao, 'codigo' => $submeta->codigo);
	    	}	

	    	$atividades_ptc = $this->getAtividadePTCTable()->getAtividadesPTCAtivas();
	    	$atividades_concluidas = $this->getAtividadePTCTable()->getAtividadesPTCConcluidas();
	    	
		}catch (\Exception $ex) {
			return $this->redirect()->toRoute('home', array(
					'action' => 'index'
			));
		}
		
    	/* INICIO Grava log */
    	$log_acao = "atividade-ptc";
    	$log_acao_id = NULL;
    	$log_acao_exibicao = 'Consulta atividades ativas de plano de trabalho ';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
	
		return new ViewModel(array(
				'atividades_ptc' => $atividades_ptc,
				'nucleos' => $nucleos,
				'areas' => $areas,
				'submetas' => $submetas,
				'atividades_concluidas' => $atividades_concluidas
		));
	}
	
	public function addAction()
	{		 
    	$request = $this->getRequest();
    	    	
    	try {
    		$areas_bd = $this->getAreaTable()->fetchAll();
    		$areas = array();
    		 
    		foreach ($areas_bd as $area){
    			$areas[$area->id] = $area->descricao;
    		}
    		 
    		$nucleos_bd = $this->getNucleoTable()->fetchAll();
    		$nucleos = array();
    		 
    		foreach ($nucleos_bd as $nucleo){
    			$nucleos[$nucleo->id] = $nucleo->descricao;
    		}
    		
    		$submetas_bd = $this->getSubmetaTable()->fetchAll();
    		$submetas = array();
    		foreach ($submetas_bd as $submeta){
    			$submetas[$submeta->id] = array('descricao' => $submeta->descricao, 'codigo' => $submeta->codigo);
    		}
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		$atividade_ptc = new AtividadePTC();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			$atividade_ptc->area_id = $dados_form['area_id'];
    			$atividade_ptc->submeta_id = $dados_form['submeta_id'];
    			$atividade_ptc->nucleo_id = '0';
				$atividade_ptc->etapa = addslashes($dados_form['etapa']);
    			$atividade_ptc->atividade = addslashes($dados_form['atividade']);
    			$atividade_ptc->inicio = implode("-",array_reverse(explode("/", $dados_form['inicio'])));
    			$atividade_ptc->fim = implode("-",array_reverse(explode("/", $dados_form['fim'])));
    			$atividade_ptc->indicador_unid = addslashes($dados_form['indicador_unid']);
    			$atividade_ptc->indicador_quant = addslashes($dados_form['indicador_quant']);
    			$atividade_ptc->indicador_desempenho = addslashes(nl2br($dados_form['indicador_desempenho']));
    			$atividade_ptc->data_inicio = implode("-",array_reverse(explode("/", $dados_form['data_inicio'])));
    			$atividade_ptc->data_fim = implode("-",array_reverse(explode("/", $dados_form['data_fim'])));
    			$atividade_ptc->fluxo_continuo = $dados_form['fluxo_continuo'];
    			$atividade_ptc->relatorio = addslashes(nl2br($dados_form['relatorio']));
    			$atividade_ptc->resultados= addslashes(nl2br($dados_form['resultados']));
				$atividade_ptc->produtos= addslashes(nl2br($dados_form['produtos']));
				$atividade_ptc->status = $dados_form['status'];
    			$atividade_ptc->del = 0;
    			
    			$atividade_ptc_id = $this->getAtividadePTCTable()->saveAtividadePTC($atividade_ptc);
    			
		    	/* INICIO Grava log */
		    	$log_acao = "atividade-ptc/add";
		    	$log_acao_id = $atividade_ptc_id;
		    	$log_acao_exibicao = 'Cadastra atividade de plano de trabalho';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */
		    	
    			return $this->redirect()->toRoute('atividade-ptc', array(
    					'action' => 'index'
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
				'nucleos' => $nucleos,
				'areas' => $areas,
    			'submetas' => $submetas
    	));
	}
	
	public function editAction()
	{		 		
    	$id = (int) $this->params()->fromRoute('id', 0);

    	if (!$id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
    	
    	try {
    		$areas_bd = $this->getAreaTable()->fetchAll();
    		$areas = array();
    		 
    		foreach ($areas_bd as $area){
    			$areas[$area->id] = $area->descricao;
    		}
    		 
    		$nucleos_bd = $this->getNucleoTable()->fetchAll();
    		$nucleos = array();
    		 
    		foreach ($nucleos_bd as $nucleo){
    			$nucleos[$nucleo->id] = $nucleo->descricao;
    		}
    		
    		$submetas_bd = $this->getSubmetaTable()->fetchAll();
    		$submetas = array();
    		foreach ($submetas_bd as $submeta){
    			$submetas[$submeta->id] = array('descricao' => $submeta->descricao, 'codigo' => $submeta->codigo);
    		}
    		
    		$atividade_ptc = $this->getAtividadePTCTable()->getAtividadePTC($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}

    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			if(isset($dados_form['area_id']) && $dados_form['area_id'] != NULL ){
    				$atividade_ptc->area_id = $dados_form['area_id'];
    			}
    			
    			if(isset($dados_form['submeta_id']) && $dados_form['submeta_id'] != NULL ){
    				$atividade_ptc->submeta_id = $dados_form['submeta_id'];
    			}
    			
    			$atividade_ptc->etapa = addslashes($dados_form['etapa']);
    			$atividade_ptc->atividade = addslashes($dados_form['atividade']);
    			$atividade_ptc->inicio = implode("-",array_reverse(explode("/", $dados_form['inicio'])));
    			$atividade_ptc->fim = implode("-",array_reverse(explode("/", $dados_form['fim'])));
    			$atividade_ptc->indicador_unid = addslashes($dados_form['indicador_unid']);
    			$atividade_ptc->indicador_quant = addslashes($dados_form['indicador_quant']);
    			$atividade_ptc->indicador_desempenho = addslashes(nl2br($dados_form['indicador_desempenho']));
    			$atividade_ptc->data_inicio = implode("-",array_reverse(explode("/", $dados_form['data_inicio'])));
    			$atividade_ptc->data_fim = implode("-",array_reverse(explode("/", $dados_form['data_fim'])));
    			$atividade_ptc->fluxo_continuo = $dados_form['fluxo_continuo'];
    			//$atividade_ptc->relatorio = addslashes(nl2br($dados_form['relatorio']));
    			$atividade_ptc->resultados= $dados_form['resultados'];
				$atividade_ptc->produtos = $dados_form['produtos'];
				$atividade_ptc->status = $dados_form['status'];
    			$atividade_ptc->del = 0;
    			
    			$this->getAtividadePTCTable()->saveAtividadePTC($atividade_ptc);

    			/* INICIO Grava log */
    			$log_acao = "atividade-ptc/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita atividade de plano de trabalho';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('atividade-ptc', array(
    					'action' => 'index'
    			));
    		}
    	}
    	    	    	
    	return new ViewModel(array(
    			'id' => $id,
				'nucleos' => $nucleos,
				'areas' => $areas,
    			'submetas' => $submetas,
    			'atividade_ptc' => $atividade_ptc
    	));
	}

	public function getSubmetasAction()
	{		
		$request = $this->getRequest();
		$response = $this->getResponse();
			
		if ($request->isPost()) {
	
			$response->setStatusCode(200);
			$area_id = $request->getPost('area');
	
			$data = $this->getSubmetaTable()->getSubmetasAtivasporArea($area_id);
	
			//$buffer = '<select  class="form-control"  name="submeta_id" id="submeta_id" required>';
			$buffer = "<option value=''>Selecione uma Submeta </option>";
	
			foreach ($data as $prov) {
				$buffer.= "<option value='".$prov['id']."'>".($prov['descricao'])."</option>";
			}
			
			//$buffer .= '</select>'; 
				
			$response->setContent($buffer);
			$headers = $response->getHeaders();
		}
		
		return $response;
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
	
		if (!$id) {
    			return $this->redirect()->toRoute('atividade-ptc', array(
    					'action' => 'index'
    			));
		}
		
		if ($id) {
				
			$this->getAtividadePTCTable()->deleteAtividadePTC($id);
		
	    	/* INICIO Grava log */
	    	$log_acao = "atividade-ptc/delete";
	    	$log_acao_id = $id;
	    	$log_acao_exibicao = 'Exclui atividade de plano de trabalho';
	    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
	    	/* FIM Grava log */

			return $this->redirect()->toRoute('atividade-ptc', array(
					'action' => 'index'
			));
		}
	
		return array(
				'id'    => $id,
				'atividade_ptc' => $this->getAtividadePTCTable()->getAtividadePTC($id)
		);
	}


	public function exportarAction()
	{
		$session_dados = new Container('usuario_dados');
	
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);
			
		//$area_id = (int) $this->params()->fromRoute('area_id', 0);
		//$submeta_id = (int) $this->params()->fromRoute('submeta_id', 0);
		/*	
		if (!$area_id) {
			return $this->redirect()->toRoute('atividade-ptc', array(
					'action' => 'index'
			));
		}
			
		if (!$submeta_id) {
			return $this->redirect()->toRoute('atividade-ptc', array(
					'action' => 'index'
			));
		}
		*/
		try {
			//$area = $this->getAreaTable()->getArea($area_id);
			//$submeta = $this->getSubmetaTable()->getSubmeta($submeta_id);
			$areas = $this->getAreaTable()->getAreasAtivas();
			/*
			 if ($_GET['area_id']>0){ $query .= " and a.area_id = $_GET[area_id]"; }
			 if ($_GET['submeta_id']>0){ $query .= " and a.submeta_id = $_GET[submeta_id]"; }
			 if ($_SESSION['permissao']==2){ $query .= " and b.id = $_SESSION[area_id]"; }
			 */
				
			$atividades_ptc = $this->getAtividadePTCTable()->getAtividadesPTCAtivas();
	
			/* INICIO Grava log */
			$log_acao = "atividade-ptc/exportar";
			$log_acao_id = NULL;
			$log_acao_exibicao = 'Exporta Atividades de Projeto';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */
	
			$viewModel->setVariables(array(
					'areas' => $areas,
				//	'area' => $area,
				//	'submeta' => $submeta,
					'atividades_ptc' => $atividades_ptc
			));
	
			return $viewModel;
	
		} catch (Exception $e) {
	
		}
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
