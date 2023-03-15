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

use Application\Model\AtividadeProjetoTable;
use Application\Model\AtividadeProjeto;

use Application\Model\NucleoTable;
use Application\Model\Nucleo;

use Application\Model\SubmetaTable;
use Application\Model\Submeta;

use Application\Model\RepresentanteTable;
use Application\Model\Representante;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\TipoVinculo;
use Application\Model\TipoVinculoTable;

use Application\Model\PermissaoTable;
use Application\Model\Permissao;


use Application\Model\LogTable;
use Application\Model\Log;

use DOMPDFModule\View\Model\PdfModel;

class RelatorioColaboradorController extends AbstractActionController
{
	protected $areaTable;
	protected $submetaTable;
	protected $nucleoTable;
	protected $usuarioTable;
	protected $representanteTable;
	protected $atividadeProjetoTable;
	protected $projetoTable;
	protected $usuarioProjetoTable;
	protected $usuarioAtividadeTable;
	protected $tipoVinculoTable;
	protected  $permissaoTable;
	protected $logTable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getTipoVinculoTable()
	{
		if (!$this->tipoVinculoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoVinculoTable = $sm->get('Application\Model\TipoVinculoTable');
		}
		return $this->tipoVinculoTable;
	}

	public function getPermissaoTable()
	{
		if (!$this->permissaoTable) {
			$sm = $this->getServiceLocator();
			$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
		}
		return $this->permissaoTable;
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

	public function getUsuarioAtividadeTable()
	{
		if (!$this->usuarioAtividadeTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioAtividadeTable = $sm->get('Application\Model\UsuarioAtividadeTable');
		}
		return $this->usuarioAtividadeTable;
	}

	public function getUsuarioProjetoTable()
	{
		if (!$this->usuarioProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioProjetoTable = $sm->get('Application\Model\UsuarioProjetoTable');
		}
		return $this->usuarioProjetoTable;
	}

	public function getRepresentanteTable()
	{
		if (!$this->representanteTable) {
			$sm = $this->getServiceLocator();
			$this->representanteTable = $sm->get('Application\Model\RepresentanteTable');
		}
		return $this->representanteTable;
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
		$representantes_bd = $this->getRepresentanteTable()->getRepresentantesByUsuarioProjeto();
		$representantes = array();

		foreach ($representantes_bd as $representante) {
			$representantes[$representante['representante_id']] = ($representante['descricao']);
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosAtivos();
		$usuarios_projetos = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$usuarios_projetos[$usuario_projeto['usuario_id']][] = array('projeto_id' => $usuario_projeto['projeto_id'], 'representante_id' => $usuario_projeto['representante_id']);
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta relatório de cumprimento de metas';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuarios' => $this->getUsuarioTable()->getUsuariosColaboradores(),
			'representantes' => $representantes,
			'usuarios_projetos' => $usuarios_projetos
		));
	}

	public function verRelatorioColaboradorAction()
	{
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);

		if (!$usuario_id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
				'action' => 'index'
			));
		}

		try {
			$usuario = $this->getUsuarioTable()->getUsuario($usuario_id);

			$projetos_bd = $this->getProjetoTable()->fetchAll();
			$projetos = array();

			foreach ($projetos_bd as $projeto) {
				$projetos[$projeto->id] = ($projeto->descricao);
			}
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
				'action' => 'index'
			));
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario_id);
		$usuarios_projetos = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$usuarios_projetos[$usuario_projeto['id']] = $usuario_projeto['projeto_id'];
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/ver-relatorio-colaborador";
		$log_acao_id = $usuario_id;
		$log_acao_exibicao = 'Consulta relatório de colaborador';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuario' => $usuario,
			'projetos' => $projetos,
			'usuarios_projetos' => $usuarios_projetos
		));
	}

	public function verRelatorioColaboradorProjetoAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		//$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0); 

		if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
				'action' => 'index'
			));
		}

		//try {
		$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);

		$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);

		$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario($usuario_projeto->projeto_id, $usuario_projeto->usuario_id);
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/ver-relatorio-colaborador-projeto";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Consulta relatório de colaborador por projeto';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'id' => $id,
			'usuario' => $usuario,
			'projeto' => $projeto,
			'usuario_projeto' => $usuario_projeto,
			'usuario_atividades_meses' => $usuario_atividades_meses
		));
	}

	public function getRelatorioServicosAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		//try {

		$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);
		$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$permissao = $this->getPermissaoTable()->getPermissao($usuario->permissao);
		$area = $this->getAreaTable()->getArea($projeto->area_id);
		// echo $usuario_projeto->usuario_id;
		// echo '<br>';
		// echo $usuario->representante_id;
		// die;
		$representante = $this->getUsuarioTable()->getUsuario($usuario->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$mes1 = $array_data[1] . "-" . $array_data[0] . "-01";
				$mes2 = $array_data[1] . "-" . $array_data[0] . "-30";
				$id_atividade = $array_data[2];


				$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);

				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

				/* INICIO Grava log */
				$log_acao = "relatorio-colaborador/get-relatorio-mensal-atividade";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Imprime relatório mensal de atividades por colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				$event = $this->getEvent();
				$request = $event->getRequest();
				$router = $event->getRouter();
				$uri = $router->getRequestUri();
				$baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $request->getBaseUrl());

				// $pdf = new PdfModel();
				// $pdf->setVariables(array(
				// 		'base_url' => $baseUrl,
				// 		'id' => $id,
				// 		'atividade'=>$atividade,
				// 		'usuario' => $usuario,						
				// 		'projeto' => $projeto,
				// 		'area' => $area,
				// 		'tipo_vinculo' => $tipo_vinculo,
				// 		'representante' => $representante,
				// 		'usuario_projeto' => $usuario_projeto,
				// 		'atividades_projeto' => $atividades_projeto,
				// 		'usuario_atividade' => $usuario_atividade,
				//         'permissao' => $permissao,
				// ));
				// return $pdf;

				$viewModel->setVariables(array(
					'id' => $id,
					'usuario' => $usuario,
					'projeto' => $projeto,
					'area' => $area,
					'tipo_vinculo' => $tipo_vinculo,
					'representante' => $representante,
					'usuario_projeto' => $usuario_projeto,
					'atividades_projeto' => $atividades_projeto,
					'usuario_atividade' => $usuario_atividade,
					'permissao' => $permissao
				));

				return $viewModel;
			}
		}
	}

	public function getRelatorioMensalAtividadeAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		//try {
		$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);
		// echo '<pre>';
		// print_r($usuario);
		// echo '</pre>';
		$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$permissao = $this->getPermissaoTable()->getPermissao($usuario->permissao);
		$area = $this->getAreaTable()->getArea($projeto->area_id);
		$representante = $this->getUsuarioTable()->getRepresentante($usuario->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$mes1 = $array_data[1] . "-" . $array_data[0] . "-01";
				$mes2 = $array_data[1] . "-" . $array_data[0] . "-30";
				$id_atividade = $array_data[2];


				$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);

				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

				/* INICIO Grava log */
				$log_acao = "relatorio-colaborador/get-relatorio-mensal-atividade";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Imprime relatório mensal de atividades por colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				$event = $this->getEvent();
				$request = $event->getRequest();
				$router = $event->getRouter();
				$uri = $router->getRequestUri();
				$baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $request->getBaseUrl());

				/*$pdf = new PdfModel();
				$pdf->setVariables(array(
						'base_url' => $baseUrl,
						'id' => $id,
						'usuario' => $usuario,
						'projeto' => $projeto,
						'area' => $area,
						'tipo_vinculo' => $tipo_vinculo,
						'representante' => $representante,
						'usuario_projeto' => $usuario_projeto,
						'atividades_projeto' => $atividades_projeto,
						'usuario_atividade' => $usuario_atividade,
                        'permissao' => $permissao,
				));
				return $pdf;*/

				$viewModel->setVariables(array(
					'id' => $id,
					'usuario' => $usuario,
					'projeto' => $projeto,
					'area' => $area,
					'tipo_vinculo' => $tipo_vinculo,
					'representante' => $representante,
					'usuario_projeto' => $usuario_projeto,
					'atividades_projeto' => $atividades_projeto,
					'usuario_atividade' => $usuario_atividade
				));

				return $viewModel;
			}
		}
	}

	public function getRelatorioMatrizResponsabilidadeAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		//try {
		$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);
		$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$area = $this->getAreaTable()->getArea($projeto->area_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$mes1 = $array_data[1] . "-" . $array_data[0] . "-01";
				$mes2 = $array_data[1] . "-" . $array_data[0] . "-30";

				$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_projeto->projeto_id, $mes1, $mes2);
				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

				/* INICIO Grava log */
				$log_acao = "relatorio-colaborador/get-relatorio-matriz-responsabilidade";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Imprime relatório matriz de responsabilidade por colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				$viewModel->setVariables(array(
					'id' => $id,
					'usuario' => $usuario,
					'projeto' => $projeto,
					'area' => $area,
					'tipo_vinculo' => $tipo_vinculo,
					'representante' => $representante,
					'usuario_projeto' => $usuario_projeto,
					'atividades_projeto' => $atividades_projeto,
					'usuario_atividade' => $usuario_atividade,
					'mes' => $array_data[0],
					'ano' => $array_data[1]
				));

				return $viewModel;
			}
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

	public function assinarRelatoriosListColaboradoresAction()
	{


		$session_dados = new Container('usuario_dados');
		$representante_id = $session_dados->id;

		$representantes_bd = $this->getRepresentanteTable()->getRepresentantesByUsuarioProjeto();
		$representantes = array();

		foreach ($representantes_bd as $representante) {
			$representantes[$representante['representante_id']] = ($representante['descricao']);
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosAtivos();
		$usuarios_projetos = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$usuarios_projetos[$usuario_projeto['usuario_id']][] = array('projeto_id' => $usuario_projeto['projeto_id'], 'representante_id' => $usuario_projeto['representante_id']);
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta relatório de cumprimento de metas';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuarios' => $this->getUsuarioTable()->getUsuariosColaboradoresPorRepresentante($representante_id),
			'representantes' => $representantes,
			'usuarios_projetos' => $usuarios_projetos
		));
	}

	public function assinarRelatoriosListProjetosAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);


		if (!isset($usuario_id)) {
			return $this->redirect()->toRoute('login', array('action' => 'index'));
		}

		try {
			$usuario = $this->getUsuarioTable()->getUsuario($usuario_id);

			$projetos_bd = $this->getProjetoTable()->fetchAll();
			$projetos = array();

			foreach ($projetos_bd as $projeto) {
				$projetos[$projeto->id] = ($projeto->descricao);
			}

			$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario_id);
			$usuarios_projetos = array();

			foreach ($usuario_projetos_bd as $usuario_projeto) {
				$usuarios_projetos[$usuario_projeto['id']] = $usuario_projeto['projeto_id'];
			}
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('home', array(
				'action' => 'index'
			));
		}

		/* INICIO Grava log */
		$log_acao = "projeto";
		$log_acao_id = $usuario->id;
		$log_acao_exibicao = 'Consulta meus projetos';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuario_id' => $usuario_id,
			'usuario' => $usuario,
			'projetos' => $projetos,
			'usuarios_projetos' => $usuarios_projetos
		));
	}


	public function assinarRelatoriosListRelatoriosAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario($projeto_id, $usuario_id);

		return new ViewModel(array(
			'usuario_atividades_meses' => $usuario_atividades_meses
		));
	}

	public function assinarRelatorioCoordenadorAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
	
		$dados_sessao_atual = new Container('usuario_dados');		

		$request = $this->getRequest();
		$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);		
		// echo '<pre>';
		// print_r($usuario_atividade);
		// echo '</pre>';
		$projeto = $this->getProjetoTable()->getProjeto($usuario_atividade->projeto_id);
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_atividade->projeto_id); //como pgar mes1 e mes2


		if ($request->isPost()) {

			$dados_form = $request->getPost();

			if ($dados_form) {


				$usuarioLogado = $this->getUsuarioTable()->getUsuarioLogin($dados_sessao_atual['cpf'], md5($dados_form['password']));

				if (is_object($usuarioLogado)) {
					
					$data_assinatura = date("Y-m-d H:i:s");
					// $usuario_atividade->usuario_id = $dados_sessao_atual->id;
					$usuario_atividade->assinatura_representante = $dados_form['hash'] . base64_encode($data_assinatura);
					$usuario_atividade->data_assinatura_representante = $data_assinatura;
					$usuario_atividade->del = 0;


					$this->getUsuarioAtividadeTable()->assinarRelatorioRepresentante($usuario_atividade);
					

					return $this->redirect()->toRoute('relatorio-colaborador/assinar-relatorios-list-relatorios', array('action' => 'assinar-relatorios-list-relatorios', 'usuario_id'=> $usuario_atividade->usuario_id, 'projeto_id' => $usuario_atividade->projeto_id));
				} else {
					return $this->redirect()->toUrl('../../relatorio-colaborador/assinar-relatorio-coordenador/' . $id . '?erro=1');
				}
			}
		}

		return new ViewModel(array(
			'id' => $id,
			'usuario_atividade' => $usuario_atividade,
			'projeto' => $projeto,
			'atividades_projeto' => $atividades_projeto,
			'dados_sessao_atual' => $dados_sessao_atual
		));
	}
}
