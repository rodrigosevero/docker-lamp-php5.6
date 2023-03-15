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

use Application\Model\AreaTable;
use Application\Model\Area;

use Application\Model\NucleoTable;
use Application\Model\Nucleo;

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\LogTable;
use Application\Model\Log;

class AreaController extends AbstractActionController
{	    	    
    protected $areaTable;	
    protected $submetaTable;
    protected $nucleoTable;
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
	
	public function getNucleoTable()
	{
		if (!$this->nucleoTable) {
			$sm = $this->getServiceLocator();
			$this->nucleoTable = $sm->get('Application\Model\NucleoTable');
		}
		return $this->nucleoTable;
	}	
    
    public function indexAction()
    {
    /*
		$areas = $this->getAreaTable()->fetchAll();
		
		foreach ($areas as $area){	
			$area->descricao = utf8_decode($area->descricao);
			$area->meta = utf8_decode($area->meta);
						
			$this->getAreaTable()->saveArea($area);
		}
		die;*/
		
    	$areas = $this->getAreaTable()->getAreasAtivas();
    	
    	$submetas_count_array = array();
    	$nucleo_count_array = array();
    	foreach ($areas as $area){
    		$submetas_count_array[$area['id']] = count($this->getSubmetaTable()->getSubmetasAtivasporArea($area['id']));
    		$nucleo_count_array[$area['id']] = count($this->getNucleoTable()->getNucleosAtivosporArea($area['id']));
    		$areas_bd[] = $area;
    	}
    	
    	/* INICIO Grava log */
    	$log_acao = "area";
    	$log_acao_id = NULL;
    	$log_acao_exibicao = 'Consulta áreas';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
    	
    	return new ViewModel(array('areas' => $areas_bd, 'submetas_count' => $submetas_count_array, 'nucleo_count' => $nucleo_count_array));
    }    
	public function addAction()
	{
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$area = new Area();
			$dados_form = $request->getPost();
			 
			if ($dados_form) {		
				
				$area->descricao= $dados_form['descricao'];
				$area->del= 0;				
				$area->meta= '';								
				$area_id = $this->getAreaTable()->saveArea($area);
				
    			/* INICIO Grava log */
    			$log_acao = "area/add";
    			$log_acao_id = $usuario_id;
    			$log_acao_exibicao = 'Cadastra área';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
	
				return $this->redirect()->toRoute('area');
			}
		}
		
		return new ViewModel(array(
    			'areas' => $this->getAreaTable()->getAreasAtivas(),
		));
	}
	public function editAction()
	{		 
		$id = (int) $this->params()->fromRoute('id', 0);
		 
		if (!$id) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
	
		try {
			$area = $this->getAreaTable()->getArea($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('area', array(
					'action' => 'index'
			));
		}
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$dados_form = $request->getPost();
			 
			if ($dados_form) {

				$area->descricao = $dados_form['descricao'];
				$area->del = 0;
				$this->getAreaTable()->saveArea($area);	
    	
		    	/* INICIO Grava log */
		    	$log_acao = "area/edit";
		    	$log_acao_id = $area->id;
		    	$log_acao_exibicao = 'Edita área';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */
	
				return $this->redirect()->toRoute('area');
			}
		}
	
		return array(
				'id' => $id,
				'area' => $area,
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
