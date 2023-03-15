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

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\AreaTable;
use Application\Model\Area;

use Application\Model\LogTable;
use Application\Model\Log;

class SubmetaController extends AbstractActionController
{	    	    
    protected $submetaTable;
    protected $areaTable;	
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
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

	public function consultaAction()
	{
    	/*
    	 $submetas = $this->getSubmetaTable()->fetchAll();
    	
    	 foreach ($submetas as $submeta){
	    	 $submeta->descricao = utf8_decode($submeta->descricao);
    	 	
    	 	$this->getSubmetaTable()->saveSubmeta($submeta);
    	 }
    	 die;
    	 */
		
		$area_id = (int) $this->params()->fromRoute('area_id', 0);
		
		if (!$area_id) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
	
		try {
			$submetas = $this->getSubmetaTable()->getSubmetasAtivasporArea($area_id);
			$area = $this->getAreaTable()->getArea($area_id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
    	
    	/* INICIO Grava log */
    	$log_acao = "submeta";
    	$log_acao_id = $area->id;
    	$log_acao_exibicao = 'Consulta submetas';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
	
		return new ViewModel(array(
				'area_id' => $area_id,
				'area' => $area,
				'submetas' => $submetas
		));
	}
	
	public function indexAction()
	{
	}
	
	public function addAction()
	{		 
    	$area_id = (int) $this->params()->fromRoute('area_id', 0);
    	$request = $this->getRequest();

    	if (!$area_id) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	try {
    		$area = $this->getAreaTable()->getArea($area_id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		$submeta = new Submeta();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			$submeta->area_id = $area_id;
    			$submeta->descricao = $dados_form['descricao'];
    			$submeta->codigo = 0;
    			$submeta->del = 0;
    			
    			$submeta_id = $this->getSubmetaTable()->saveSubmeta($submeta);
    			$submeta->id = $submeta_id;
    			$submeta->codigo = 'SUBMETA '.$submeta_id;
    			
    			$this->getSubmetaTable()->saveSubmeta($submeta);
    			    	
		    	/* INICIO Grava log */
		    	$log_acao = "submeta/add";
		    	$log_acao_id = $submeta_id;
		    	$log_acao_exibicao = 'Cadastra submeta';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */
		    	
    			return $this->redirect()->toRoute('submeta/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
    			'area_id' => $area_id,
         	 	'area' => $area
    	));
	}
	
	public function editAction()
	{		 
    	$area_id = (int) $this->params()->fromRoute('area_id', 0);
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$request = $this->getRequest();

    	if (!$area_id) {
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
    		$area = $this->getAreaTable()->getArea($area_id);
    		$submeta = $this->getSubmetaTable()->getSubmeta($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		//$submeta = new Submeta();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			$submeta->descricao = $dados_form['descricao'];
    			$submeta->del = 0;
    			$this->getSubmetaTable()->saveSubmeta($submeta);

    			/* INICIO Grava log */
    			$log_acao = "submeta/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita submeta';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('submeta/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
    		}
    	}
    	    	
    	return new ViewModel(array(
    			'id' => $id,
    			'area_id' => $area_id,
         	 	'area' => $area,
    			'submeta' => $submeta
    	));
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
    	$area_id = (int) $this->params()->fromRoute('area_id', 0);
	
		if (!$id) {
    			return $this->redirect()->toRoute('submeta/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
		}
		
		if ($id) {
				
			$this->getSubmetaTable()->deleteSubmeta($id);

			/* INICIO Grava log */
			$log_acao = "submeta/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui submeta';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('submeta/consulta', array(
					'action' => 'consulta', 'area_id' => $area_id
			));
		}
	
		return array(
				'id'    => $id,
				'representante' => $this->getRepresentanteTable()->getRepresentante($id)
		);
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
