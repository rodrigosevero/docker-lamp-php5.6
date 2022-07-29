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

use Application\Model\LogTable;
use Application\Model\Log;

use Application\Model\PermissaoTable;
use Application\Model\Permissao;

use Application\Model\AreaFuncaoTable;
use Application\Model\AreaFuncao;

use Application\Model\FuncaoTable;
use Application\Model\Funcao;

use Application\Model\Usuario;
use Application\Model\UsuarioTable;

use Application\Model\BonificacaoTable;
use Application\Model\Bonificacao;

use Application\Model\BolsaTable;
use Application\Model\Bolsa;



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
	protected $logTable;
	protected $permissaoTable;
	protected $areaFuncaoTable;
	protected $funcaoTable;
	protected $bolsaTable;

	public function getBonificacaoTable()
	{
		if (!$this->funcaoTable) {
			$sm = $this->getServiceLocator();
			$this->bonificacaoTable = $sm->get('Application\Model\BonificacaoTable');
		}
		return $this->bonificacaoTable;
	}

	public function getBolsaTable()
	{
		if (!$this->bolsaTable) {
			$sm = $this->getServiceLocator();
			$this->bolsaTable = $sm->get('Application\Model\BolsaTable');
		}
		return $this->bolsaTable;
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

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getPermissaoTable()
	{
		if (!$this->permissaoTable) {
			$sm = $this->getServiceLocator();
			$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
		}
		return $this->permissaoTable;
	}

	public function getTipoVinculoTable()
	{
		if (!$this->tipoVinculoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoVinculoTable = $sm->get('Application\Model\TipoVinculoTable');
		}
		return $this->tipoVinculoTable;
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
			'usuarios' => $this->getUsuarioTable()->getUsuariosColaboradoresRelatorio(),
			'total' => count($this->getUsuarioTable()->getUsuariosColaboradoresRelatorio()),
			'representantes' => $representantes,
			'usuarios_projetos' => $usuarios_projetos
		));
	}


	public function atividadeMensalAction()
	{
		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();
			$mes = $dados_form['mes'];
			$ano = $dados_form['ano'];
		} else {
			$mes = date('m');
			$ano = date('Y');
		}
		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/atividade-mensal";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta de relatório de quem fez a atividade mensal';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuarios' => $this->getUsuarioTable()->getRelatorioMensaldeAtividades($mes, $ano),
			'mes' => $mes,
			'ano' => $ano

		));
	}

	public function funcoesAction()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		if ($_GET['t'] == 1) {
			$funcoes = $this->getAreaFuncaoTable()->getAreaFuncoesGeral();
		} else {
			$funcoes = $this->getAreaFuncaoTable()->getAreaFuncoesBolsista();
		}
		// $bonificacoes = $this->getBonificacaoTable()->getBonificacoesGeral();


		$viewModel->setVariables(array(
			'funcoes' => $funcoes,
			// 'bonificacoes' => $bonificacoes,
		));

		return $viewModel;
	}

	public function bolsistaAction()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$bolsistas = $this->getBolsaTable()->getBolsasGeral();



		$viewModel->setVariables(array(
			'bolsistas' => $bolsistas,
			// 'bonificacoes' => $bonificacoes,
		));

		return $viewModel;
	}

	public function bonificacoesAction()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		// $funcoes = $this->getAreaFuncaoTable()->getAreaFuncoesGeral();
		$bonificacoes = $this->getBonificacaoTable()->getBonificacoesGeral();
		// print_r($bonificacoes);
		// die;


		$viewModel->setVariables(array(
			// 'funcoes' => $funcoes,
			'bonificacoes' => $bonificacoes,
		));

		return $viewModel;
	}

	public function exportarAtividadeMensalAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);
		$mes = (int) $this->params()->fromRoute('mes', 0);
		$ano = (int) $this->params()->fromRoute('ano', 0);
		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/exportar-atividade-mensal";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Exporta atividade mensal colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$viewModel->setVariables(array(
			'usuarios' => $this->getUsuarioTable()->getRelatorioMensaldeAtividades($mes, $ano),
		));

		return $viewModel;
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




		$projetos_especificos_bd = $this->getProjetoTable()->fetchAll();
		$projetos_especificos = array();

		foreach ($projetos_especificos_bd as $projeto_especifico) {
			$projetos_especificos[$projeto_especifico->id] = ($projeto_especifico->descricao);
		}

		$usuario_projetos_especificos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosEspecificosAtivos($usuario_id);
		$usuarios_projetos_especificos = array();

		foreach ($usuario_projetos_especificos_bd as $usuario_projeto_especifico) {
			$usuarios_projetos_especificos[$usuario_projeto_especifico['id']] = $usuario_projeto_especifico['projeto_id'];
		}


		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/ver-relatorio-colaborador";
		$log_acao_id = $usuario_id;
		$log_acao_exibicao = 'Consulta relatório de colaborador';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */
		$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario1($usuario_id);
		return new ViewModel(array(
			'usuario_atividades_meses' => $usuario_atividades_meses,
			'usuario' => $usuario,
			'projetos' => $projetos,
			'usuarios_projetos' => $usuarios_projetos,
			'projetos_especificos' => $projetos_especificos,
			'usuarios_projetos_especificos' => $usuarios_projetos_especificos,

		));
	}


	public function verRelatorioEstagiariosAction()
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
			'usuarios' => $this->getUsuarioTable()->getUsuariosEstagiarios(),
			'representantes' => $representantes,
			'usuarios_projetos' => $usuarios_projetos
		));
	}

	public function verRelatorioColaboradorProjetoAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'index'
			));
		}*/

		//try {
		$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);

		$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);

		$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario($projeto_id, $usuario_projeto->usuario_id);
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
			'projeto_id' => $projeto_id,
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

		if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
				'action' => 'index'
			));
		}

		try {
			$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
			$usuario = $this->getUsuarioTable()->getUsuario($usuario_projeto->usuario_id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
				'action' => 'index'
			));
		}

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {
				$array_data = explode('/', $dados_form['mes']);

				$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

				/* INICIO Grava log */
				$log_acao = "relatorio-colaborador/get-relatorio-servicos";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Imprime relatório de serviços por colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				$viewModel->setVariables(array(
					'id' => $id,
					'usuario' => $usuario,
					'usuario_projeto' => $usuario_projeto,
					'usuario_atividade' => $usuario_atividade
				));

				return $viewModel;
			}
		}
	}

	public function getRelatorioMensalAtividadeAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		$atividades_bd =  $this->getAtividadeProjetoTable()->fetchAll();
		$atividades = array();
		foreach ($atividades_bd as $atividade) {
			$atividades[$atividade->id] = $atividade->descricao;
		}

		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($usuario->id);
		$usuario_projetos = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);

		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}


		$array_data = explode('/', $dados_form['mes']);

		//				$mes1 = $atividade->data_inicial;//$array_data[1]."-".$array_data[0]."-01";
		//				$mes2 = $atividade->data_final;//$array_data[1]."-".$array_data[0]."-30";
		//
		//				//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//				//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

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
						'atividade'=>$atividade,
						'usuario' => $usuario,
                        'area' => $area,
						'tipo_vinculo' => $tipo_vinculo,
						'representante' => $representante,
						'atividades_projeto' => $atividades_projeto,
                        'atividade_usuario_projeto' => $atividade_usuario_projeto,
						'usuario_projetos_bd' => $usuario_projetos,
				));
				return $pdf;*/

		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'representante' => $representante,
			'atividades_projeto' => $atividades_projeto,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos,
		));

		return $viewModel;
	}


	public function assinarRelatorioMensalAtividadeAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		$atividades_bd =  $this->getAtividadeProjetoTable()->fetchAll();
		$atividades = array();
		foreach ($atividades_bd as $atividade) {
			$atividades[$atividade->id] = $atividade->descricao;
		}

		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($usuario->id);
		$usuario_projetos = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);

		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}


		$array_data = explode('/', $dados_form['mes']);

		//				$mes1 = $atividade->data_inicial;//$array_data[1]."-".$array_data[0]."-01";
		//				$mes2 = $atividade->data_final;//$array_data[1]."-".$array_data[0]."-30";
		//
		//				//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//				//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

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

		//        $pdf = new PdfModel();
		//        $pdf->setVariables(array(
		//            'base_url' => $baseUrl,
		//            'id' => $id,
		//            'atividade'=>$atividade,
		//            'usuario' => $usuario,
		//            'area' => $area,
		//            'tipo_vinculo' => $tipo_vinculo,
		//            'representante' => $representante,
		//            'atividades_projeto' => $atividades_projeto,
		//            'atividade_usuario_projeto' => $atividade_usuario_projeto,
		//            'usuario_projetos_bd' => $usuario_projetos,
		//        ));
		//        return $pdf;

		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'representante' => $representante,
			'atividades_projeto' => $atividades_projeto,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos,
		));

		return $viewModel;
	}



	public function getRelatorioEstagioAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		$atividades_bd =  $this->getAtividadeProjetoTable()->fetchAll();
		$atividades = array();
		foreach ($atividades_bd as $atividade) {
			$atividades[$atividade->id] = $atividade->descricao;
		}

		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($usuario->id);
		$usuario_projetos = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);

		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}


		$array_data = explode('/', $dados_form['mes']);

		//				$mes1 = $atividade->data_inicial;//$array_data[1]."-".$array_data[0]."-01";
		//				$mes2 = $atividade->data_final;//$array_data[1]."-".$array_data[0]."-30";
		//
		//				//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//				//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);

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
		//         'base_url' => $baseUrl,
		//         'id' => $id,
		//         'atividade'=>$atividade,
		//         'usuario' => $usuario,
		//         'area' => $area,
		//         'tipo_vinculo' => $tipo_vinculo,
		//         'representante' => $representante,
		//         'atividades_projeto' => $atividades_projeto,
		//         'atividade_usuario_projeto' => $atividade_usuario_projeto,
		//         'usuario_projetos_bd' => $usuario_projetos,
		// ));
		// return $pdf;

		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'representante' => $representante,
			'atividades_projeto' => $atividades_projeto,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos,
		));

		return $viewModel;
	}

	public function getRelatorioMatrizResponsabilidadeAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		//$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		//$representante = $this->getRepresentanteTable()->getRepresentante($usuario_projeto->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$permissao = $this->getPermissaoTable()->getPermissao($usuario->permissao);

		$area_funcao = $this->getAreaFuncaoTable()->getAreaFuncaoPorColaborador($usuario->id);				
		$funcao = $this->getFuncaoTable()->getFuncao($area_funcao->funcao_id);
		
		if($usuario->bolsa_id){
		$bolsa = $this->getBolsaTable()->getBolsa($usuario->bolsa_id);
		} else {
			$bolsa = 0;
		}

		// print_r($bolsa); die;

		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($atividade->projeto_id);
		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($atividade->usuario_id);

		$array_data = explode('/', $dados_form['mes']);

		$mes1 = $atividade->data_inicial; //$array_data[1]."-".$array_data[0]."-01";
		$mes2 = $atividade->data_final; //$array_data[1]."-".$array_data[0]."-30";

		//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);


		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/get-relatorio-matriz-responsabilidade";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprime relatório mensal de matriz de resposabilidade';
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
						'atividade'=>$atividade,
						'usuario' => $usuario,
                        'usuario1' => $usuario1,
						'projeto' => $projeto,
						'area' => $area,
						'tipo_vinculo' => $tipo_vinculo,
						'representante' => $representante,
						'usuario_projeto' => $usuario_projeto,
						'atividades_projeto' => $atividades_projeto,
						'usuario_atividade' => $usuario_atividade,
						'atividade_usuario_projeto' => $atividade_usuario_projeto,
						'usuario_projetos_bd' => $usuario_projetos_bd,
				));
				return $pdf;*/


		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'usuario1' => $usuario1,
			'projeto' => $projeto,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'permissao' => $permissao,
			'representante' => $representante,
			'usuario_projeto' => $usuario_projeto,
			'atividades_projeto' => $atividades_projeto,
			'usuario_atividade' => $usuario_atividade,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos_bd,
			'funcao'=>$funcao
		));

		return $viewModel;
	}


	public function getRelatorioEspecificoAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		//try {
		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		//$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		//$representante = $this->getRepresentanteTable()->getRepresentante($usuario_projeto->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$area_funcao = $this->getAreaFuncaoTable()->getAreaFuncaoPorColaborador($usuario->id);				
		if(count($area_funcao) > 0){
		$funcao = $this->getFuncaoTable()->getFuncao($area_funcao->funcao_id);
		}
		if($usuario->bolsa_id){
		$bolsa = $this->getBolsaTable()->getBolsa($usuario->bolsa_id);
		} else {
			$bolsa = 0;
		}
		
		// print_r($tipo_vinculo); die;
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($atividade->projeto_id);

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetoEspecificoAtivos($atividade->usuario_id, $projeto_id);

		$array_data = explode('/', $dados_form['mes']);

		$mes1 = $atividade->data_inicial; //$array_data[1]."-".$array_data[0]."-01";
		$mes2 = $atividade->data_final; //$array_data[1]."-".$array_data[0]."-30";

		//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);


		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosEspecificosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/get-relatorio-matriz-responsabilidade";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprime relatório mensal de matriz de resposabilidade';
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
						'atividade'=>$atividade,
						'usuario' => $usuario,
                        'usuario1' => $usuario1,
						'projeto' => $projeto,
						'area' => $area,
						'tipo_vinculo' => $tipo_vinculo,
						'representante' => $representante,
						'usuario_projeto' => $usuario_projeto,
						'atividades_projeto' => $atividades_projeto,
						'usuario_atividade' => $usuario_atividade,
						'atividade_usuario_projeto' => $atividade_usuario_projeto,
						'usuario_projetos_bd' => $usuario_projetos_bd,
				));
				return $pdf;*/

		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'usuario1' => $usuario1,
			'projeto' => $projeto,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'representante' => $representante,
			'usuario_projeto' => $usuario_projeto,
			'atividades_projeto' => $atividades_projeto,
			'usuario_atividade' => $usuario_atividade,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos_bd,
			'count_area_funcao' => count($area_funcao),
			'funcao' => $funcao,
			'bolsa' => $bolsa
		));

		return $viewModel;
	}


	public function getRelatorioEspecificoAction1()
	{
		$dados_sessao_atual = new Container('usuario_dados');
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$id = (int) $this->params()->fromRoute('id', 0);

		/*if (!$id) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		//try {
		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
		//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		//$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
		$area = $this->getAreaTable()->getArea($usuario->area_id);
		//$representante = $this->getRepresentanteTable()->getRepresentante($usuario_projeto->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('relatorio-colaborador', array(
					'action' => 'index'
			));
		}*/

		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($atividade->projeto_id);
		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosEspecificosAtivos($atividade->usuario_id);

		$array_data = explode('/', $dados_form['mes']);

		$mes1 = $atividade->data_inicial; //$array_data[1]."-".$array_data[0]."-01";
		$mes2 = $atividade->data_final; //$array_data[1]."-".$array_data[0]."-30";

		//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMensaisAtivasByProjeto($usuario_projeto->projeto_id, $mes1, $mes2);
		//$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividadeByProjetoByUsuarioByData($usuario_projeto->projeto_id, $usuario_projeto->usuario_id, $array_data[0], $array_data[1]);


		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($usuario->id);
		$atividade_usuario_projeto = array();

		foreach ($usuario_projetos_bd1 as $usuario_projeto) {
			$atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $usuario->id);
		}

		/* INICIO Grava log */
		$log_acao = "relatorio-colaborador/get-relatorio-especifico";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprime relatório mensal especifico';
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
						'atividade'=>$atividade,
						'usuario' => $usuario,
                        'usuario1' => $usuario1,
						'projeto' => $projeto,
						'area' => $area,
						'tipo_vinculo' => $tipo_vinculo,
						'representante' => $representante,
						'usuario_projeto' => $usuario_projeto,
						'atividades_projeto' => $atividades_projeto,
						'usuario_atividade' => $usuario_atividade,
						'atividade_usuario_projeto' => $atividade_usuario_projeto,
						'usuario_projetos_bd' => $usuario_projetos_bd,
				));
				return $pdf;*/

		$viewModel->setVariables(array(
			'base_url' => $baseUrl,
			'id' => $id,
			'atividade' => $atividade,
			'usuario' => $usuario,
			'usuario1' => $usuario1,
			'projeto' => $projeto,
			'area' => $area,
			'tipo_vinculo' => $tipo_vinculo,
			'representante' => $representante,
			'usuario_projeto' => $usuario_projeto,
			'atividades_projeto' => $atividades_projeto,
			'usuario_atividade' => $usuario_atividade,
			'atividade_usuario_projeto' => $atividade_usuario_projeto,
			'usuario_projetos_bd' => $usuario_projetos_bd,
		));

		return $viewModel;
	}


	public function getRelatorioMatrizResponsabilidadeAction_bkp()
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

		/*$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
			//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
    		$usuario = $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id);
            $representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
    		//$projeto = $this->getProjetoTable()->getProjeto($usuario_projeto->projeto_id);
    		$area = $this->getAreaTable()->getArea($usuario->area_id);
    		//$representante = $this->getRepresentanteTable()->getRepresentante($usuario_projeto->representante_id);
    		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
			*/
		$atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);

		//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
		$usuario = $this->getUsuarioTable()->getUsuario($atividade->usuario_id);
		$projeto = $this->getProjetoTable()->getProjeto($atividade->projeto_id);
		$area = $this->getAreaTable()->getArea($projeto->area_id);
		$representante = $this->getRepresentanteTable()->getRepresentante($usuario->representante_id);
		//$representante = $this->getRepresentanteTable()->getRepresentante($usuario_projeto->representante_id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$permissao = $this->getPermissaoTable()->getPermissao($usuario->permissao);
		print($permissao);
		die;
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
					'permissao' => $permissao,
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
}
