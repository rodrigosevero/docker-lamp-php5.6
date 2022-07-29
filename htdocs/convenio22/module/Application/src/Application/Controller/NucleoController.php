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

use Application\Model\NucleoTable;
use Application\Model\Nucleo;

use Application\Model\AreaTable;
use Application\Model\Area;

use Application\Model\UsuarioTable;
use Application\Model\Usuario;

use Application\Model\LogTable;
use Application\Model\Log;

class NucleoController extends AbstractActionController
{	    	    
    protected $nucleoTable;
    protected $areaTable;	
    protected $usuarioTable;		
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}		
    
	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}	
    
	public function getAreaTable()
	{
		if (!$this->areaTable) {
			$sm = $this->getServiceLocator();
			$this->areaTable = $sm->get('Application\Model\AreaTable');
		}
		return $this->areaTable;
	}	
    	
        
	public function getNucleoTable()
	{
		if (!$this->nucleoTable) {
			$sm = $this->getServiceLocator();
			$this->nucleoTable = $sm->get('Application\Model\NucleoTable');
		}
		return $this->nucleoTable;
	}	

	public function consultaAction()
	{
    	/*
    	 $nucleos = $this->getNucleoTable()->fetchAll();
    	
    	 foreach ($nucleos as $nucleo){
	    	 $nucleo->descricao = utf8_decode($nucleo->descricao);
    	 	
    	 	$this->getNucleoTable()->saveNucleo($nucleo);
    	 }
    	 die;*/
    	 
		$area_id = (int) $this->params()->fromRoute('area_id', 0);
		
		if (!$area_id) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
	
		try {
			$nucleos = $this->getNucleoTable()->getNucleosAtivosporArea($area_id);
			$area = $this->getAreaTable()->getArea($area_id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
	
		$coordenadores_nucleo = array();
		$usuarios_coordenadores = $this->getUsuarioTable()->getUsuariosByPermissao('4'); //o id do perfil 'coordenadores de nucleo' é 4
		foreach ($usuarios_coordenadores as $usuario){
			$coordenadores_nucleo[$usuario->id] = $usuario->nome; 
		}
    	
    	/* INICIO Grava log */
    	$log_acao = "nucleo";
    	$log_acao_id = $area->id;
    	$log_acao_exibicao = 'Consulta núcleos';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
		
		return new ViewModel(array(
				'area_id' => $area_id,
				'area' => $area,
				'coordenadores_nucleo' => $coordenadores_nucleo, 
				'nucleos' => $nucleos
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
    		$nucleo = new Nucleo();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			$nucleo->area_id = $area_id;
    			$nucleo->descricao = $dados_form['descricao'];
    			$nucleo->coordenador_id = $dados_form['coordenador_id'];
                $nucleo->del = 0;
    			
    			$nucleo_id = $this->getNucleoTable()->saveNucleo($nucleo);

    			/* INICIO Grava log */
    			$log_acao = "nucleo/add";
    			$log_acao_id = $nucleo_id;
    			$log_acao_exibicao = 'Cadastra núcleo';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('nucleo/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
    		}
    	}
    	
		$coordenadores_nucleo = array();
		$usuarios_coordenadores = $this->getUsuarioTable()->getUsuariosByPermissao('4'); //o id do perfil 'coordenadores de nucleo' é 4
		foreach ($usuarios_coordenadores as $usuario){
			$coordenadores_nucleo[$usuario->id] = $usuario->nome; 
		}
    	    	
    	return new ViewModel(array(
    			'area_id' => $area_id,
         	 	'area' => $area,
				'coordenadores_nucleo' => $coordenadores_nucleo
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
    		$nucleo = $this->getNucleoTable()->getNucleo($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('area', array(
    				'action' => 'index'
    		));
    	}
    	
    	if ($request->isPost()) {
    		//$nucleo = new Nucleo();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {

    			$nucleo->descricao = $dados_form['descricao'];    			
    			if ($dados_form['coordenador_id']!=""){$nucleo->coordenador_id = $dados_form['coordenador_id'];} else {$nucleo->coordenador_id=0;}
    			$nucleo->del = '0';
    			$this->getNucleoTable()->saveNucleo($nucleo);

    			/* INICIO Grava log */
    			$log_acao = "nucleo/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita núcleo';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
    			return $this->redirect()->toRoute('nucleo/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
    		}
    	}

    	$coordenadores_nucleo = array();
    	$usuarios_coordenadores = $this->getUsuarioTable()->getUsuariosByPermissao('4'); //o id do perfil 'coordenadores de nucleo' é 4
    	foreach ($usuarios_coordenadores as $usuario){
    		$coordenadores_nucleo[$usuario->id] = $usuario->nome;
    	}
    	
    	return new ViewModel(array(
    			'id' => $id,
    			'area_id' => $area_id,
         	 	'area' => $area,
    			'nucleo' => $nucleo,
				'coordenadores_nucleo' => $coordenadores_nucleo
    	));
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
    	$area_id = (int) $this->params()->fromRoute('area_id', 0);
	
		if (!$id) {
    			return $this->redirect()->toRoute('nucleo/consulta', array(
    					'action' => 'consulta', 'area_id' => $area_id
    			));
		}
		
		if ($id) {
				
			$this->getNucleoTable()->deleteNucleo($id);

			/* INICIO Grava log */
			$log_acao = "nucleo/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui núcleo';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('nucleo/consulta', array(
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
