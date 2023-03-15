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

use Application\Model\AtividadePTCProjetoTable;
use Application\Model\AtividadePTCProjeto;

use Application\Model\AtividadePTCTable;
use Application\Model\AtividadePTC;

use Application\Model\AtividadeProjetoTable;
use Application\Model\AtividadeProjeto;

use Application\Model\ProjetoTable;
use Application\Model\Projeto;

use Application\Model\SubmetaTable;
use Application\Model\Submeta;
use Zend\View\Model\JsonModel;

use Application\Model\LogTable;
use Application\Model\Log;

class AtividadeProjetoController extends AbstractActionController
{	    	    
    protected $atividadePTCProjetoTable;
    protected $atividadePTCTable;
    protected $atividadeProjetoTable;
    protected $projetoTable;
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
    
	public function getProjetoTable()
	{
		if (!$this->projetoTable) {
			$sm = $this->getServiceLocator();
			$this->projetoTable = $sm->get('Application\Model\ProjetoTable');
		}
		return $this->projetoTable;
	}	
            
	public function getAtividadeProjetoTable()
	{
		if (!$this->atividadeProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->atividadeProjetoTable = $sm->get('Application\Model\AtividadeProjetoTable');
		}
		return $this->atividadeProjetoTable;
	}	
            
	public function getAtividadePTCTable()
	{
		if (!$this->atividadePTCTable) {
			$sm = $this->getServiceLocator();
			$this->atividadePTCTable = $sm->get('Application\Model\AtividadePTCTable');
		}
		return $this->atividadePTCTable;
	}	
            
	public function getAtividadePTCProjetoTable()
	{
		if (!$this->atividadePTCProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->atividadePTCProjetoTable = $sm->get('Application\Model\AtividadePTCProjetoTable');
		}
		return $this->atividadePTCProjetoTable;
	}	

	public function getSubmetaTable()
	{
		if (!$this->submetaTable) {
			$sm = $this->getServiceLocator();
			$this->submetaTable = $sm->get('Application\Model\SubmetaTable');
		}
		return $this->submetaTable;
	}

	public function consultaAction()
	{		
    	/*
    	 $atividade_projetos = $this->getAtividadeProjetoTable()->fetchAll();
    	
    	 foreach ($atividade_projetos as $atividade_projeto){
	    	 $atividade_projeto->descricao = utf8_encode($atividade_projeto->descricao);
	    	 $atividade_projeto->descricao = utf8_decode($atividade_projeto->descricao);
	    	     	 	
    	 	$this->getAtividadeProjetoTable()->saveAtividadeProjeto($atividade_projeto);
    	 }
    	 die;
    	 */
		
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		
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
		
		if (!$projeto_id) {
			return $this->redirect()->toRoute('projeto', array(
					'action' => 'index'
			));
		}
	
		try {
			$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto_id);
			$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('projeto', array(
					'action' => 'index'
			));
		}
		
    	/* INICIO Grava log */
    	$log_acao = "atividade-projeto/consulta";
    	$log_acao_id = $projeto_id;
    	$log_acao_exibicao = 'Consulta atividades de projeto';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
	
		return new ViewModel(array(
				'projeto_id' => $projeto_id,
				'projeto' => $projeto,
				'atividades_projeto' => $atividades_projeto,
				'nucleos' => $nucleos,
				'areas' => $areas
		));
	}
	
	public function indexAction()
	{
	}
	
	public function addAction()
	{		 
    	$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
    	$request = $this->getRequest();
    	
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

    	if (!$projeto_id) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	try {
    		$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		$atividade_projeto = new AtividadeProjeto();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			$atividade_projeto->projeto_id = $projeto_id;
    			$atividade_projeto->dt_inicial = implode("-",array_reverse(explode("/", $dados_form['dt_inicial'])));
    			$atividade_projeto->dt_final = implode("-",array_reverse(explode("/", $dados_form['dt_final'])));
    			$atividade_projeto->ptc = $dados_form['ptc'];
    			$atividade_projeto->epe = $dados_form['epe'];
    			$atividade_projeto->descricao = $dados_form['descricao'];
    			$atividade_projeto->prev_inicio = implode("-",array_reverse(explode("/", $dados_form['prev_inicio'])));
    			$atividade_projeto->prev_fim = implode("-",array_reverse(explode("/", $dados_form['prev_fim'])));
    			$atividade_projeto->prazo = $dados_form['prazo'];
    			
    			$atividade_projeto->inativo = 0;
    			$atividade_projeto->del = 0;
    			
    			$atividade_projeto_id = $this->getAtividadeProjetoTable()->saveAtividadeProjeto($atividade_projeto);
    			
		    	/* INICIO Grava log */
		    	$log_acao = "atividade-projeto/add";
		    	$log_acao_id = $atividade_projeto_id;
		    	$log_acao_exibicao = 'Cadastra atividade de projeto';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */
		    	
    			return $this->redirect()->toRoute('atividade-projeto/consulta', array(
    					'action' => 'consulta', 'projeto_id' => $projeto_id
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
    			'projeto_id' => $projeto_id,
         	 	'projeto' => $projeto,
				'nucleos' => $nucleos,
				'areas' => $areas
    	));
	}
	
	public function editAction()
	{		 
    	$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$request = $this->getRequest();

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

    	$atividades_ptc_bd = $this->getAtividadePTCTable()->getAtividadesPTCAtivas();
    	
    	$atividades_ptc = array();
	    foreach ($atividades_ptc_bd as $atividade_ptc){
	    	$atividades_ptc[$atividade_ptc['id']] = array('area_id' => $atividade_ptc['area_id'], 'atividade' => ($atividade_ptc['atividade']));
	    }
    	
    	if (!$projeto_id) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	if (!$id) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	try {
    		$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
    		$atividade_projeto = $this->getAtividadeProjetoTable()->getAtividadeProjeto($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('projeto', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		//$atividade_projeto = new AtividadeProjeto();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {    			
    			
    			$atividade_projeto->projeto_id = $projeto_id;
    			$atividade_projeto->dt_inicial = implode("-",array_reverse(explode("/", $dados_form['dt_inicial'])));
    			$atividade_projeto->dt_final = implode("-",array_reverse(explode("/", $dados_form['dt_final'])));
    			$atividade_projeto->ptc = $dados_form['ptc'];
    			$atividade_projeto->epe = $dados_form['epe'];
    			$atividade_projeto->descricao = $dados_form['descricao'];
    			$atividade_projeto->prev_inicio = implode("-",array_reverse(explode("/", $dados_form['prev_inicio'])));
    			$atividade_projeto->prev_fim = implode("-",array_reverse(explode("/", $dados_form['prev_fim'])));    			
    			$atividade_projeto->prazo = $dados_form['prazo'];
				$atividade_projeto->del = '0';
    			    			
    			$this->getAtividadeProjetoTable()->saveAtividadeProjeto($atividade_projeto);
    			
    			if(is_array($dados_form['atividade_ptc_id']) && $dados_form['atividade_ptc_id'][0] != ""){
    				foreach($dados_form['atividade_ptc_id'] as $atividade_ptc_id){
    					$atividade_ptc_projeto = new AtividadePTCProjeto();
    					$atividade_ptc_projeto->area_id = 0;
    					$atividade_ptc_projeto->atividade_projeto = $id;
    					$atividade_ptc_projeto->atividade_ptc_id = $atividade_ptc_id;
    					$atividade_ptc_projeto->del = 0;
    			
    					$this->getAtividadePTCProjetoTable()->saveAtividadePTC($atividade_ptc_projeto);
    				}
    			}
    			
		    	/* INICIO Grava log */
		    	$log_acao = "atividade-projeto/edit";
		    	$log_acao_id = $id;
		    	$log_acao_exibicao = 'Edita atividade de projeto';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('atividade-projeto/consulta', array(
    					'action' => 'consulta', 'projeto_id' => $projeto_id
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
    			'id' => $id,
    			'projeto_id' => $projeto_id,
         	 	'projeto' => $projeto,
    			'atividade_projeto' => $atividade_projeto,
				'nucleos' => $nucleos,
				'areas' => $areas,
    			'atividades_ptc' => $atividades_ptc,
    			'submetas' => $submetas,
    			'atividades_ptc_projeto' => $this->getAtividadePTCProjetoTable()->getAtividadesPTCProjetoAtivasByAtividade($id)
    	));
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
    	//$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
    	$atividade_projeto = $this->getAtividadeProjetoTable()->getAtividadeProjeto($id);
	
		if (!$id) {
    			return $this->redirect()->toRoute('projeto', array(
    					'action' => 'index'
    			));
		}
		
		if ($id) {
				
			$this->getAtividadeProjetoTable()->deleteAtividadeProjeto($id);
			
		    /* INICIO Grava log */
		    $log_acao = "atividade-projeto/delete";
		    $log_acao_id = $id;
		    $log_acao_exibicao = 'Exclui atividade de projeto';
		    $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    /* FIM Grava log */

		    return $this->redirect()->toRoute('atividade-projeto/consulta', array(
		    		'action' => 'consulta', 'projeto_id' => $atividade_projeto->projeto_id
		    ));		    
		}
	
		return array(
				'id'    => $id,
				'atividade_projeto' => $atividade_projeto
		);
	}	

	public function getAtividadesProjetoAction()
	{
		$request = $this->getRequest();
		$response = $this->getResponse();
			
		if ($request->isPost()) {
	
			$response->setStatusCode(200);
			$projeto_id = $request->getPost('projeto_id');
	
			$data = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto_id);
	
			//$buffer="<option value=''>Selecione uma Atividade </option>";
	
			foreach ($data as $prov) {
				$buffer.= "<option style='font-size:11px' value='".$prov['id']."'>".($prov['descricao'])."</option>";
			}
	
			$response->setContent($buffer);
			$headers = $response->getHeaders();
		}
		return $response;
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
