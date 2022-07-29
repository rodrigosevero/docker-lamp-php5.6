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

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\AtividadePTCTable;
use Application\Model\AtividadePTC;

use Application\Model\AtividadeProjetoable;
use Application\Model\AtividadeProjeto;

use Application\Model\CumprimentoObjetoTable;
use Application\Model\CumprimentoObjeto;

use Application\Model\CumprimentoObjetoAtividadeTable;
use Application\Model\CumprimentoObjetoAtividade;

use Application\Model\ProjetoTable;
use Application\Model\Projeto;

use Application\Model\ArtefatoTable;
use Application\Model\Artefato;


use Application\Model\LogTable;
use Application\Model\Log;

class RelatorioCumprimentoObjetoController extends AbstractActionController
{
	protected $areaTable;
	protected $submetaTable;
	protected $atividadePTCTable;
	protected $atividadeProjetoTable;
	protected $cumprimentoObjetoTable;
	protected $cumprimentoObjetoAtividadeTable;
	protected $projetoTable;
	protected $logTable;
	protected $artefatoTable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
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

	public function getAtividadeProjetoTable()
	{
		if (!$this->atividadeProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->atividadeProjetoTable = $sm->get('Application\Model\AtividadeProjetoTable');
		}
		return $this->atividadeProjetoTable;
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

	public function getAtividadePTCTable()
	{
		if (!$this->atividadePTCTable) {
			$sm = $this->getServiceLocator();
			$this->atividadePTCTable = $sm->get('Application\Model\AtividadePTCTable');
		}
		return $this->atividadePTCTable;
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

	public function indexAction()
	{
		$usuario_dados = new Container('usuario_dados');

		// if($usuario_dados->permissao==12){
		//     $areas = $this->getAreaTable()->getArea1($usuario_dados->area_id);
		// } else if($usuario_dados->permissao==4){
		//     $areas = $this->getAreaTable()->getArea1($usuario_dados->area_id);
		// }else {
		//     $areas = $this->getAreaTable()->getAreasAtivas();
		// }
		$areas = $this->getAreaTable()->getAreasAtivas();
		return new ViewModel(array('areas' => $areas));
	}

	public function consultaAction()
	{
		$usuario_dados = new Container('usuario_dados');
		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {


				$area = $this->getAreaTable()->getArea($dados_form['area_id']);
				$submeta = $this->getSubmetaTable()->getSubmeta($dados_form['submeta_id']);
				if ($usuario_dados->permissao == 12) {
					//$projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatrizByArea($usuario_dados->area_id);
					$areas = $this->getAreaTable()->getArea1($usuario_dados->area_id);
				} else if ($usuario_dados->permissao == 4) {
					//$projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatrizByCoordenadorNucleo($usuario_dados->area_id, $usuario_dados->nucleo);
					$areas = $this->getAreaTable()->getArea1($usuario_dados->area_id);
				} else {
					$areas = $this->getAreaTable()->getAreasAtivas();
				}

				$atividades_ptc = $this->getAtividadePTCTable()->getAtividadesPTCAtivasByAreaBySubmeta($area->id, $submeta->id);

				/* INICIO Grava log */
				$log_acao = "relatorio-cumprimento-objeto/consulta";
				$log_acao_id = NULL;
				$log_acao_exibicao = 'Consulta Relatório simplificado de cumprimento do objeto';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return new ViewModel(array('areas' => $areas, 'area' => $area, 'submeta' => $submeta, 'atividades_ptc' => $atividades_ptc));
			}
		}
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
				$buffer .= "<option value='" . $prov['id'] . "'>" . ($prov['descricao']) . "</option>";
			}

			//$buffer .= '</select>';

			$response->setContent($buffer);
			$headers = $response->getHeaders();
		}

		return $response;
	}

	public function exportarRelatorioCumprimentoObjetoAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$area_id = (int) $this->params()->fromRoute('area_id', 0);
		$submeta_id = (int) $this->params()->fromRoute('submeta_id', 0);

		if (!$area_id) {
			return $this->redirect()->toRoute('relatorio-cumprimento-objeto', array(
				'action' => 'index'
			));
		}

		if (!$submeta_id) {
			return $this->redirect()->toRoute('relatorio-cumprimento-objeto', array(
				'action' => 'index'
			));
		}

		try {
			$area = $this->getAreaTable()->getArea($area_id);
			$submeta = $this->getSubmetaTable()->getSubmeta($submeta_id);
			$areas = $this->getAreaTable()->getAreasAtivas();

			$atividades_ptc = $this->getAtividadePTCTable()->getAtividadesPTCAtivasByAreaBySubmeta($area->id, $submeta->id);

			/* INICIO Grava log */
			$log_acao = "relatorio-cumprimento-objeto/exportar-relatorio-cumprimento-objeto";
			$log_acao_id = NULL;
			$log_acao_exibicao = 'Exporta Relatório simplificado de cumprimento do objeto';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			$viewModel->setVariables(array(
				'areas' => $areas,
				'area' => $area,
				'submeta' => $submeta,
				'atividades_ptc' => $atividades_ptc
			));

			return $viewModel;
		} catch (Exception $e) {
		}
	}

	public function getRelatorioCumprimentoObjetoAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('atividade_id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('atividade-ptc', array(
				'action' => 'index'
			));
		}

		//try {
		$atividade_ptc = $this->getAtividadePTCTable()->getAtividadePTC($id);
		$cumprimento_objeto = $this->getCumprimentoObjetoTable()->getLastCumprimentoObjetoByAtividade($atividade_ptc->id);
		$area = $this->getAreaTable()->getArea($atividade_ptc->area_id);
		$submeta = $this->getSubmetaTable()->getSubmeta($atividade_ptc->submeta_id);

		$cumprimento_objeto_atividades = $this->getCumprimentoObjetoAtividadeTable()->getCumprimentoObjetoAtividadesAtivosByAtividadePTC($id);
		$artefatos = $this->getArtefatoTable()->getArtefatosAtivosByArea($id);

		$projetos_ativos = $this->getProjetoTable()->getProjetos();

		$projetos_bd = $this->getProjetoTable()->getProjetosByAtividadePTC($id);
		$projetos = array();

		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto['projeto_id']] = array('descricao' => $projeto['projeto'], 'meta' => $projeto['meta']);
		}

		$atividades_projeto_bd = $this->getAtividadeProjetoTable()->fetchAll();
		$atividades_projeto = array();

		foreach ($atividades_projeto_bd as $atividade_projeto) {
			$atividades_projeto[$atividade_projeto->id] = $atividade_projeto->descricao;
		}
		//    	}
		//    	catch (\Exception $ex) {
		//    		return $this->redirect()->toRoute('atividade-ptc', array(
		//    				'action' => 'index'
		//    		));
		//    	}

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$mes1 = $array_data[1] . "-" . $array_data[0] . "-01";
				$mes2 = $array_data[1] . "-" . $array_data[0] . "-30";

				$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);
			}
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-cumprimento-objeto/get-relatorio-cumprimento-objeto";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprime relatório de cumprimento de objeto';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$viewModel->setVariables(array(
			'id' => $id,
			'atividade_ptc' => $atividade_ptc,
			'area' => $area,
			'submeta' => $submeta,
			'cumprimento_objeto' => $cumprimento_objeto,
			'cumprimento_objeto_atividades' => $cumprimento_objeto_atividades,
			'atividades_projeto' => $atividades_projeto,
			'projetos' => $projetos,
			'artefatos' => $artefatos,

		));

		return $viewModel;
	}



	public function getRelatorioCumprimentoObjetoWordAction()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('atividade_id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('atividade-ptc', array(
				'action' => 'index'
			));
		}

		//try {
		$atividade_ptc = $this->getAtividadePTCTable()->getAtividadePTC($id);
		$cumprimento_objeto = $this->getCumprimentoObjetoTable()->getLastCumprimentoObjetoByAtividade($atividade_ptc->id);
		$area = $this->getAreaTable()->getArea($atividade_ptc->area_id);
		$submeta = $this->getSubmetaTable()->getSubmeta($atividade_ptc->submeta_id);

		$cumprimento_objeto_atividades = $this->getCumprimentoObjetoAtividadeTable()->getCumprimentoObjetoAtividadesAtivosByAtividadePTC($id);
		$artefatos = $this->getArtefatoTable()->getArtefatosAtivosByArea($id);

		$projetos_ativos = $this->getProjetoTable()->getProjetos();

		$projetos_bd = $this->getProjetoTable()->getProjetosByAtividadePTC($id);
		$projetos = array();

		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto['projeto_id']] = array('descricao' => $projeto['projeto'], 'meta' => $projeto['meta']);
		}

		$atividades_projeto_bd = $this->getAtividadeProjetoTable()->fetchAll();
		$atividades_projeto = array();

		foreach ($atividades_projeto_bd as $atividade_projeto) {
			$atividades_projeto[$atividade_projeto->id] = $atividade_projeto->descricao;
		}
		//    	}
		//    	catch (\Exception $ex) {
		//    		return $this->redirect()->toRoute('atividade-ptc', array(
		//    				'action' => 'index'
		//    		));
		//    	}

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$mes1 = $array_data[1] . "-" . $array_data[0] . "-01";
				$mes2 = $array_data[1] . "-" . $array_data[0] . "-30";

				$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);
			}
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-cumprimento-objeto/get-relatorio-cumprimento-objeto";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprime relatório de cumprimento de objeto';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */


		$viewModel->setVariables(array(
			'id' => $id,
			'atividade_ptc' => $atividade_ptc,
			'area' => $area,
			'submeta' => $submeta,
			'cumprimento_objeto' => $cumprimento_objeto,
			'cumprimento_objeto_atividades' => $cumprimento_objeto_atividades,
			'atividades_projeto' => $atividades_projeto,
			'projetos' => $projetos,
			'artefatos' => $artefatos,

		));

		return $viewModel;

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
