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

use Application\Model\AreaFuncaoTable;
use Application\Model\AreaFuncao;
use Application\Model\FuncaoTable;
use Application\Model\Funcao;

class AreaController extends AbstractActionController
{
	protected $areaTable;
	protected $submetaTable;
	protected $nucleoTable;
	protected $logTable;
	protected $areaFuncaoTable;
	protected $funcaoTable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getAreaFuncaoTable()
	{
		if (!$this->areaFuncaoTable) {
			$sm = $this->getServiceLocator();
			$this->areaFuncaoTable = $sm->get('Application\Model\AreaFuncaoTable');
		}
		return $this->areaFuncaoTable;
	}

	public function getFuncaoTable()
	{
		if (!$this->funcaoTable) {
			$sm = $this->getServiceLocator();
			$this->funcaoTable = $sm->get('Application\Model\FuncaoTable');
		}
		return $this->funcaoTable;
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
		foreach ($areas as $area) {
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


	public function funcaoAction()
	{
		/*
    	 $nucleos = $this->getNucleoTable()->fetchAll();
    	
    	 foreach ($nucleos as $nucleo){
	    	 $nucleo->descricao = utf8_decode($nucleo->descricao);
    	 	
    	 	$this->getNucleoTable()->saveNucleo($nucleo);
    	 }
    	 die;*/

		$id = (int) $this->params()->fromRoute('id', 0);
		$areaFuncoes = $this->getAreaFuncaoTable()->getAreaFuncoes($id);

		/* INICIO Grava log */
		$log_acao = "area/funcao";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Consulta funções da área';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'id' => $id,
			'areaFuncoes' => $areaFuncoes,
		));
	}


	public function funcaoAddAction()
	{

		$id = (int) $this->params()->fromRoute('id', 0);


		$request = $this->getRequest();

		if ($request->isPost()) {
			$area_funcao = new AreaFuncao();
			$dados_form = $request->getPost();

			if ($dados_form) {

				$area_funcao->funcao_id = $dados_form['funcao_id'];
				$area_funcao->area_id = $id;
				$area_funcao->del = 0;
				$area_funcao_id = $this->getAreaFuncaoTable()->saveAreaFuncao($area_funcao);

				/* INICIO Grava log */
				$log_acao = "area/funcao-add";
				$log_acao_id = $area_funcao_id;
				$log_acao_exibicao = 'Cadastrar função na área';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('area/funcao', array('id'=>$id));
			}
		}
		
		$funcoes = $this->getFuncaoTable()->getFuncoes2();
		


		/* INICIO Grava log */
		$log_acao = "area/funcao-add";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Adicionar funções à área';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'id' => $id,
			'funcoes' => $funcoes,
		));
	}


	public function funcaoDeleteAction()
	{

		echo $id = (int) $this->params()->fromRoute('id', 0);
		echo '<br>';
		echo $area_id = (int) $this->params()->fromRoute('area_id', 0);		
		
		$this->getAreaFuncaoTable()->deleteAreaFuncao($id);
		
		/* INICIO Grava log */
		$log_acao = "area/funcao-delete";
		$log_acao_id = $id;
		$log_acao_exibicao = 'deletar funções da área';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return $this->redirect()->toRoute('area/funcao', array('id'=>$area_id));
		
	}

	public function addAction()
	{
		$request = $this->getRequest();

		if ($request->isPost()) {
			$area = new Area();
			$dados_form = $request->getPost();

			if ($dados_form) {

				$area->descricao = $dados_form['descricao'];
				$area->del = 0;
				$area->meta = '';
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
		} catch (\Exception $ex) {
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
