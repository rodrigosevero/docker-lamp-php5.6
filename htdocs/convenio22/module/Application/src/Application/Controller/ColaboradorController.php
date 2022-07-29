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
use Zend\Db\ResultSet\ResultSet;

use Application\Model\UsuarioTable;
use Application\Model\Usuario;

use Application\Model\UsuarioProjetoTable;
use Application\Model\UsuarioProjeto;

use Application\Model\Area;
use Application\Model\AreaTable;

use Application\Model\TipoVinculo;
use Application\Model\TipoVinculoTable;

use Application\Model\RepresentanteTable;
use Application\Model\Representante;

use Application\Model\RepresentanteUsuarioTable;
use Application\Model\RepresentanteUsuario;

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;


use Application\Model\LogTable;
use Application\Model\Log;

use Application\Model\AreaFuncaoTable;
use Application\Model\AreaFuncao;
use Application\Model\AreaFuncaoHistoricoTable;
use Application\Model\AreaFuncaoHistorico;

use Application\Model\FuncaoTable;
use Application\Model\Funcao;

use Application\Model\Bonificacao;
use Application\Model\BonificacaoTable;

use Application\Model\Bolsa;
use Application\Model\BolsaTable;

use Application\Model\DocumentoUsuario;
use Application\Model\DocumentoUsuarioTable;



class ColaboradorController extends AbstractActionController
{
	protected $usuarioTable;
	protected $usuarioProjetoTable;
	protected $areaTable;
	protected $tipoVinculoTable;
	protected $representanteTable;
	protected $permissaoTable;
	protected $projetoTable;
	protected $usuarioAtividadeTable;
	protected $representanteUsuarioTable;
	protected $logTable;
	protected $areaFuncaoTable;
	protected $areaFuncaoHistoricoTable;
	protected $funcaoTable;
	protected $documentoUsuarioTable;
	protected $bolsaTable;

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

	public function getDocumentoUsuarioTable()
	{
		if (!$this->documentoUsuarioTable) {
			$sm = $this->getServiceLocator();
			$this->documentoUsuarioTable = $sm->get('Application\Model\DocumentoUsuarioTable');
		}
		return $this->documentoUsuarioTable;
	}

	public function getAreaFuncaoHistoricoTable()
	{
		if (!$this->areaFuncaoHistoricoTable) {
			$sm = $this->getServiceLocator();
			$this->areaFuncaoHistoricoTable = $sm->get('Application\Model\AreaFuncaoHistoricoTable');
		}
		return $this->areaFuncaoHistoricoTable;
	}

	public function getFuncaoTable()
	{
		if (!$this->funcaoTable) {
			$sm = $this->getServiceLocator();
			$this->funcaoTable = $sm->get('Application\Model\FuncaoTable');
		}
		return $this->funcaoTable;
	}

	public function getBonificacaoTable()
	{
		if (!$this->bonificacaoTable) {
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

	public function getUsuarioAtividadeTable()
	{
		if (!$this->usuarioAtividadeTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioAtividadeTable = $sm->get('Application\Model\UsuarioAtividadeTable');
		}
		return $this->usuarioAtividadeTable;
	}

	public function getRepresentanteUsuarioTable()
	{
		if (!$this->representanteUsuarioTable) {
			$sm = $this->getServiceLocator();
			$this->representanteUsuarioTable = $sm->get('Application\Model\RepresentanteUsuarioTable');
		}
		return $this->representanteUsuarioTable;
	}

	public function getProjetoTable()
	{
		if (!$this->projetoTable) {
			$sm = $this->getServiceLocator();
			$this->projetoTable = $sm->get('Application\Model\ProjetoTable');
		}
		return $this->projetoTable;
	}





	public function getPermissaoTable()
	{
		if (!$this->permissaoTable) {
			$sm = $this->getServiceLocator();
			$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
		}
		return $this->permissaoTable;
	}

	public function getRepresentanteTable()
	{
		if (!$this->representanteTable) {
			$sm = $this->getServiceLocator();
			$this->representanteTable = $sm->get('Application\Model\RepresentanteTable');
		}
		return $this->representanteTable;
	}

	public function getTipoVinculoTable()
	{
		if (!$this->tipoVinculoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoVinculoTable = $sm->get('Application\Model\TipoVinculoTable');
		}
		return $this->tipoVinculoTable;
	}

	public function getAreaTable()
	{
		if (!$this->areaTable) {
			$sm = $this->getServiceLocator();
			$this->areaTable = $sm->get('Application\Model\AreaTable');
		}
		return $this->areaTable;
	}

	public function getUsuarioProjetoTable()
	{
		if (!$this->usuarioProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioProjetoTable = $sm->get('Application\Model\UsuarioProjetoTable');
		}
		return $this->usuarioProjetoTable;
	}

	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}

	public function indexAction()
	{

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();

		foreach ($areas_bd as $area) {
			$areas[$area->id] = $area->descricao;
		}

		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();

		foreach ($representantes_bd as $representante) {
			$representantes[$representante->id] = $representante->nome;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();

		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto->id] = $projeto->descricao;
		}



		$tipo_vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$tipo_vinculos = array();

		foreach ($tipo_vinculos_bd as $tipo_vinculo) {
			$tipo_vinculos[$tipo_vinculo->id] = $tipo_vinculo->descricao;
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByColaborador();
		$colaborador_representantes = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$colaborador_representantes[$usuario_projeto['usuario_id']][] = $usuario_projeto['representante_id'];
			$colaborador_representantes[$usuario_projeto['usuario_id']] = array_unique($colaborador_representantes[$usuario_projeto['usuario_id']]);

			$colaborador_projetos[$usuario_projeto['usuario_id']][] = $usuario_projeto['projeto_id'];
			$colaborador_projetos[$usuario_projeto['usuario_id']] = array_unique($colaborador_projetos[$usuario_projeto['usuario_id']]);
		}

		$colaboradores_bd = $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca);
		$representantes_usuario = array();
		foreach ($colaboradores_bd as $colaboradores1) {
			$representantes_usuario[$colaboradores1['usuario_id']][] = $colaboradores1['representante_id'];
			$representantes_usuario[$colaboradores1['usuario_id']] = array_unique($representantes_usuario[$colaboradores1['id']]);
		}

		$areas_bd1 = $this->getAreaTable()->fetchAll();


		if ($_GET['meta'] != "") {
			$meta = $_GET['meta'];
		}
		if ($_GET['status'] != "") {
			$status_busca = $_GET['status'];
		}
		if ($_GET['tipo_vinculo'] != "") {
			$tipo_vinculo_busca = $_GET['tipo_vinculo'];
		}

		/* INICIO Grava log */
		$log_acao = "colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'colaboradores' => $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca, $meta),
			'areas' => $areas,
			'metas' => $areas_bd1,
			'tipo_vinculo_busca' => $tipo_vinculo_busca,
			'status_busca' => $status_busca,
			'tipo_vinculos' => $tipo_vinculos,
			'colaborador_representantes' => $colaborador_representantes,
			'representantes' => $representantes,
			'projetos' => $projetos,
			'colaborador_projetos' => $colaborador_projetos,
			'representantes_usuario' => $representantes_usuario,
			'meta' => $meta,
			'tipo_vinculos_bd' => $this->getTipoVinculoTable()->fetchAll(),
		));
	}


	public function uniselvaAction()
	{

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();

		foreach ($areas_bd as $area) {
			$areas[$area->id] = $area->descricao;
		}

		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();

		foreach ($representantes_bd as $representante) {
			$representantes[$representante->id] = $representante->nome;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();

		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto->id] = $projeto->descricao;
		}



		$tipo_vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$tipo_vinculos = array();

		foreach ($tipo_vinculos_bd as $tipo_vinculo) {
			$tipo_vinculos[$tipo_vinculo->id] = $tipo_vinculo->descricao;
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByColaborador();
		$colaborador_representantes = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$colaborador_representantes[$usuario_projeto['usuario_id']][] = $usuario_projeto['representante_id'];
			$colaborador_representantes[$usuario_projeto['usuario_id']] = array_unique($colaborador_representantes[$usuario_projeto['usuario_id']]);

			$colaborador_projetos[$usuario_projeto['usuario_id']][] = $usuario_projeto['projeto_id'];
			$colaborador_projetos[$usuario_projeto['usuario_id']] = array_unique($colaborador_projetos[$usuario_projeto['usuario_id']]);
		}

		$colaboradores_bd = $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca);
		$representantes_usuario = array();
		foreach ($colaboradores_bd as $colaboradores1) {
			$representantes_usuario[$colaboradores1['usuario_id']][] = $colaboradores1['representante_id'];
			$representantes_usuario[$colaboradores1['usuario_id']] = array_unique($representantes_usuario[$colaboradores1['id']]);
		}

		$areas_bd1 = $this->getAreaTable()->fetchAll();


		if ($_GET['meta'] != "") {
			$meta = $_GET['meta'];
		}
		if ($_GET['status'] != "") {
			$status_busca = $_GET['status'];
		}
		if ($_GET['tipo_vinculo'] != "") {
			$tipo_vinculo_busca = $_GET['tipo_vinculo'];
		}

		/* INICIO Grava log */
		$log_acao = "colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'colaboradores' => $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca, $meta),
			'areas' => $areas,
			'metas' => $areas_bd1,
			'tipo_vinculo_busca' => $tipo_vinculo_busca,
			'status_busca' => $status_busca,
			'tipo_vinculos' => $tipo_vinculos,
			'colaborador_representantes' => $colaborador_representantes,
			'representantes' => $representantes,
			'projetos' => $projetos,
			'colaborador_projetos' => $colaborador_projetos,
			'representantes_usuario' => $representantes_usuario,
			'meta' => $meta,
			'tipo_vinculos_bd' => $this->getTipoVinculoTable()->fetchAll(),
		));
	}


	public function uniselvaViewAction()
	{
		$session_dados = new Container('usuario_dados');
		$id = (int) $this->params()->fromRoute('id', 0);

		$colaborador = $this->getUsuarioTable()->getUsuario($id);
		$reprensentante_id_banco = $colaborador->representante_id;
		if ($colaborador->area_id != "") {
			$funcoes_por_area = $this->getAreaFuncaoTable()->getAreaFuncoes($colaborador->area_id);
		}

		$funcao_id2 = $colaborador->cargo_funcao;
		// echo '<pre>';
		// print_r($colaborador);
		// echo '</pre>';
		$representante_id2 = $colaborador->representante_id;


		$request = $this->getRequest();
		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {


				$representante_id = $dados_form['representante_id'];
				$colaborador->nome = $dados_form['nome'];
				$i = explode(" ", $colaborador->nome);
				$sobrenome = $i[1];
				$colaborador->email = $dados_form['email'];
				$colaborador->tel_fixo = $dados_form['tel_fixo'];
				$colaborador->tel_movel = $dados_form['tel_movel'];
				$colaborador->cpf = $dados_form['cpf'];
				$colaborador->razao_social = $dados_form['razao_social'];
				$colaborador->cnpj = $dados_form['cnpj'];
				$colaborador->area_id = $dados_form['area_id'];
				$colaborador->area_id2 = $dados_form['area_id2'];
				$colaborador->vinculo = $dados_form['vinculo'];
				$colaborador->curso_estagio = $dados_form['curso_estagio'];
				$colaborador->professor_estagio = $dados_form['professor_estagio'];
				$colaborador->instituicao = $dados_form['instituicao'];
				$colaborador->cargo_funcao = $dados_form['cargo_funcao'];
				$colaborador->unidade_lotacao = $dados_form['unidade_lotacao'];
				$colaborador->status_enquadramento_funcional = $dados_form['status_enquadramento_funcional'];
				$colaborador->representante_id = $dados_form['representante_id'];
				$colaborador->representante_id2 = $dados_form['representante_id2'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->permissao = $dados_form['permissao'];
				$colaborador->siape = $dados_form['siape'];
				if (isset($dados_form['data_admissao']) && $dados_form['data_admissao'] != NULL) {
					$colaborador->data_admissao = date('Y-m-d', strtotime($dados_form['data_admissao']));
				}
				$colaborador->status = $dados_form['status'];
				if ($dados_form['data_inatividade']) {
					$colaborador->data_inatividade = $dados_form['data_inatividade'];
					//date('Y-m-d', strtotime($dados_form['data_inativadade']));
				}
				// print_r($colaborador);
				// die;

				$colaborador->atualizado = '1';
				$colaborador->superadmin = '0';
				$colaborador->parcelas = $dados_form['parcelas'];
				$colaborador->termo_autorizacao = $dados_form['termo_autorizacao'];

				//FAZ UPLOAD DO ANEXO
				/* Diretorio Local */
				$sistema = "convenio21";
				//$sistema = "convenio_producao_28_12_17";
				$diretorio = __DIR__;
				$diretorio = str_replace("\\", '/', $diretorio);
				$diretorio = explode($sistema, $diretorio);
				/* Diretorio Local */
				//echo $diretorio[0].$sistema; die;				
				/* Diretorio Local */

				$file = $request->getFiles()->toArray();
				if ($_FILES['arquivo']['error'] == 0) {
					unlink($sistema . '/public/colaborador-anexo/' . $colaborador->arquivo);
					$ext = strtolower(end(explode(".", $file['arquivo']['name'])));
					$colaborador->arquivo = md5(time()) . '.' . $ext;
					echo $target_file = $diretorio[0] . $sistema . '/public/colaborador-anexo/' . $colaborador->arquivo;



					if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) {
						echo "ok";
					} else {

						echo "erro";
					}
				} else {

					if ($dados_form['delete_anexo'] == 1) {
						$nomeArquivo = $sistema . '/public/colaborador-anexo/' . $colaborador->arquivo;
						unlink($nomeArquivo);
						$colaborador->arquivo = NULL;
					}
				}

				$this->getUsuarioTable()->saveUsuario($colaborador);

				// Salva colaborador na tabela area_funcao				
				$usuario_id = $id;
				$funcao_id = $dados_form['cargo_funcao'];
				$this->getAreaFuncaoTable()->saveColaborador($usuario_id, $funcao_id, $funcao_id2);

				// salva historico da movimentação da função				
				if ($funcao_id != "") {
					if ($funcao_id2 != $funcao_id) {
						$areaFuncaoHistorico = new AreaFuncaoHistorico();
						$areaFuncaoHistorico->funcao_id = $funcao_id;
						$areaFuncaoHistorico->colaborador_id = $colaborador->id;
						$areaFuncaoHistorico->area_id = $colaborador->area_id;
						$areaFuncaoHistorico->data = date("Y-m-d");
						$areaFuncaoHistorico->hora = date("H:i:s");
						$areaFuncaoHistorico->usuario_id = $session_dados->id;
						$areaFuncaoHistorico->del = 0;
						$this->getAreaFuncaoHistoricoTable()->saveAreaFuncaoHistorico($areaFuncaoHistorico);
					}
				}



				//Salva histórico representante
				if ($representante_id2 != $colaborador->representante_id) {
					$usuario_id = $id;
					$representante_id = $dados_form['representante_id'];
					$usuario_update = $session_dados['id'];
					if ($reprensentante_id_banco != $representante_id) {
						$this->getUsuarioTable()->saveRepresentanteUsuario($usuario_id, $representante_id, $usuario_update);
					}
				}



				/* INICIO Grava log */
				$log_acao = "colaborador/edit";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Edita colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */
				// die;
				return $this->redirect()->toRoute('colaborador');
			}
		}

		return array(
			'id' => $id,
			'colaborador' => $colaborador,
			'tipo_vinculos' => $this->getTipoVinculoTable()->getTipoVinculosAtivos(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'representantes2' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
			'areas2' => $this->getAreaTable()->getAreasAtivas(),
			'funcoes_por_area' => $funcoes_por_area,
			'bonificacoes' => $this->getBonificacaoTable()->getBonificacoes(),
			'documentos' => $this->getDocumentoUsuarioTable()->getDocumentosPorUsuario($id),
		);
	}

	public function folhaPontoAction()
	{

		$session_dados = new Container('usuario_dados');
		$usuario_id = (int) $session_dados->id;

		//$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);

		if (!$usuario_id) {
			return $this->redirect()->toRoute('index', array(
				'action' => 'index'
			));
		}

		$usuario = $this->getUsuarioTable()->getUsuario($session_dados->id);
		$arr_meses = array(
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'05' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);


		/* INICIO Grava log */
		$log_acao = "colaborador/folha-ponto";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Consulta tela de folha-ponto';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'usuario_id' => $usuario_id,
			'usuario' => $usuario,
			'arr_meses' => $arr_meses,
		));
	}

	public function getFolhaPontoAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$session_dados = new Container('usuario_dados');
		$usuario_id = (int) $session_dados->id;

		$usuario = $this->getUsuarioTable()->getUsuario($session_dados->id);
		$tipo_vinculo = $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo);
		$arr_meses = array(
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'05' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);

		$request = $this->getRequest();

		if ($request->isPost()) {

			$dados_form = $request->getPost();


			if ($dados_form) {

				$mes = $dados_form['mes'];
			}
		}

		/* INICIO Grava log */
		$log_acao = "colaborador/get-folha-ponto";
		$log_acao_id = $id;
		$log_acao_exibicao = 'Imprimir folha-ponto';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$funcao = $this->getAreaFuncaoHistoricoTable()->getFuncaoColaborador($usuario->id);
		// print_r($funcao);
		// die;


		$viewModel->setVariables(array(
			'usuario_id' => $usuario_id,
			'usuario' => $usuario,
			'tipo_vinculo' => $this->getTipoVinculoTable()->getTipoVinculo($usuario->vinculo),
			'arr_meses' => $arr_meses,
			'mes' => $mes,
			'session_dados' => $session_dados,
			'funcao' => $funcao

		));

		return $viewModel;
	}

	public function addAction()
	{
		$request = $this->getRequest();

		if ($request->isPost()) {
			$colaborador = new Usuario();
			$dados_form = $request->getPost();

			if ($dados_form) {

				$colaborador->nome = $dados_form['nome'];
				$i = explode(" ", $colaborador->nome);
				$sobrenome = $i[1];
				$colaborador->email = $dados_form['email'];
				$colaborador->tel_fixo = $dados_form['tel_fixo'];
				$colaborador->tel_movel = $dados_form['tel_movel'];
				$colaborador->senha = md5('ufmt2017');
				$colaborador->cpf = $dados_form['cpf'];
				$colaborador->razao_social = $dados_form['razao_social'];
				$colaborador->cnpj = $dados_form['cnpj'];
				$colaborador->area_id = $dados_form['area_id'];
				$colaborador->area_id2 = $dados_form['area_id2'];
				$colaborador->vinculo = $dados_form['vinculo'];
				$colaborador->curso_estagio = $dados_form['curso_estagio'];
				$colaborador->professor_estagio = $dados_form['professor_estagio'];
				$colaborador->instituicao = $dados_form['instituicao'];
				$colaborador->cargo_funcao = $dados_form['cargo_funcao'];
				$colaborador->unidade_lotacao = $dados_form['unidade_lotacao'];
				$colaborador->status_enquadramento_funcional = $dados_form['status_enquadramento_funcional'];
				$colaborador->representante_id = $dados_form['representante_id'];
				$colaborador->representante_id2 = $dados_form['representante_id2'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->permissao = $dados_form['permissao'];
				$colaborador->termo_autorizacao = $dados_form['termo_autorizacao'];
				$colaborador->parcelas = $dados_form['parcelas'];
				$colaborador->data_admissao = date('Y-m-d', strtotime($dados_form['data_admissao']));
				$colaborador->del = 0;
				$colaborador->atualizado = 0;
				$colaborador->data_atualizado = 0;
				$colaborador->hora_atualizado = 0;
				$colaborador->status = 'ativo';
				$colaborador->siape = $dados_form['siape'];
				$colaborador->data_inatividade = 0;
				$colaborador->superadmin = 0;
				// print_r($colaborador); die;
				$usuario_id = $this->getUsuarioTable()->saveUsuario($colaborador);

				foreach ($dados_form['projeto_id'] as $projeto_id) {
					$usuario_projeto = new UsuarioProjeto();
					$usuario_projeto->projeto_id = $projeto_id;
					$usuario_projeto->usuario_id = $usuario_id;
					$usuario_projeto->representante_id = 0;
					$usuario_projeto->del = 0;
					$this->getUsuarioProjetoTable()->saveUsuarioProjeto($usuario_projeto);
				}

				/* INICIO Grava log */
				$log_acao = "colaborador/add";
				$log_acao_id = $usuario_id;
				$log_acao_exibicao = 'Cadastra colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('colaborador');
			}
		}

		return new ViewModel(array(
			'tipo_vinculos' => $this->getTipoVinculoTable()->getTipoVinculosAtivos(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'representantes2' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
			'areas2' => $this->getAreaTable()->getAreasAtivas(),
		));
	}


	public function editAction()
	{
		$session_dados = new Container('usuario_dados');
		$id = (int) $this->params()->fromRoute('id', 0);

		$colaborador = $this->getUsuarioTable()->getUsuario($id);
		$projetos = $this->getProjetoTable()->getProjetos();
		$projetos1 = $this->getProjetoTable()->getProjetos();
		$reprensentante_id_banco = $colaborador->representante_id;
		if ($colaborador->area_id != "") {
			$funcoes_por_area = $this->getAreaFuncaoTable()->getAreaFuncoes($colaborador->area_id);
		}

		$funcao_id2 = $colaborador->cargo_funcao;

		$representante_id2 = $colaborador->representante_id;


		$request = $this->getRequest();
		if ($request->isPost()) {

			$dados_form = $request->getPost();

			if ($dados_form) {
				$representante_id = $dados_form['representante_id'];
				$colaborador->nome = $dados_form['nome'];
				$i = explode(" ", $colaborador->nome);
				$sobrenome = $i[1];
				$colaborador->email = $dados_form['email'];
				$colaborador->tel_fixo = $dados_form['tel_fixo'];
				$colaborador->tel_movel = $dados_form['tel_movel'];
				$colaborador->cpf = $dados_form['cpf'];
				$colaborador->razao_social = $dados_form['razao_social'];
				$colaborador->cnpj = $dados_form['cnpj'];
				$colaborador->area_id = $dados_form['area_id'];
				$colaborador->area_id2 = $dados_form['area_id2'];
				$colaborador->vinculo = $dados_form['vinculo'];
				$colaborador->curso_estagio = $dados_form['curso_estagio'];
				$colaborador->professor_estagio = $dados_form['professor_estagio'];
				$colaborador->instituicao = $dados_form['instituicao'];
				$colaborador->cargo_funcao = $dados_form['cargo_funcao'];
				if (isset($dados_form['bonificacao_id']) != NULL) {
					$colaborador->bonificacao_id = $dados_form['bonificacao_id'];
				}
				if (isset($dados_form['bonificacao_projeto']) != NULL) {
					$colaborador->bonificacao_projeto = $dados_form['bonificacao_projeto'];
				}

				if ($dados_form['bolsa_id'] != "") {
					$colaborador->bolsa_id = $dados_form['bolsa_id'];
				} else {
					$colaborador->bolsa_id = NULL;
				}
				if ($dados_form['bolsa_projeto']) {
					$colaborador->bolsa_projeto = $dados_form['bolsa_projeto'];
				}

							


				$colaborador->unidade_lotacao = $dados_form['unidade_lotacao'];
				$colaborador->status_enquadramento_funcional = $dados_form['status_enquadramento_funcional'];
				$colaborador->representante_id = $dados_form['representante_id'];
				$colaborador->representante_id2 = $dados_form['representante_id2'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->permissao = $dados_form['permissao'];
				$colaborador->siape = $dados_form['siape'];
				if (isset($dados_form['data_admissao']) && $dados_form['data_admissao'] != NULL) {
					$colaborador->data_admissao = date('Y-m-d', strtotime($dados_form['data_admissao']));
				}
				$colaborador->status = $dados_form['status'];
				if ($dados_form['data_inatividade']) {
					$colaborador->data_inatividade = $dados_form['data_inatividade'];
				} else {
					$colaborador->data_inatividade = NULL;
				}
				// echo '<pre>';
				// print_r($colaborador);
				// echo '</pre>';
				// die;

				$colaborador->atualizado = '1';
				$colaborador->superadmin = '0';
				$colaborador->parcelas = $dados_form['parcelas'];
				$colaborador->termo_autorizacao = $dados_form['termo_autorizacao'];



				$this->getUsuarioTable()->saveUsuario($colaborador);

				// Salva colaborador na tabela area_funcao				
				$usuario_id = $id;
				$funcao_id = $dados_form['cargo_funcao'];
				$this->getAreaFuncaoTable()->saveColaborador($usuario_id, $funcao_id, $funcao_id2);

				// salva historico da movimentação da função				
				if ($funcao_id != "") {
					if ($funcao_id2 != $funcao_id) {
						$areaFuncaoHistorico = new AreaFuncaoHistorico();
						$areaFuncaoHistorico->funcao_id = $funcao_id;
						$areaFuncaoHistorico->colaborador_id = $colaborador->id;
						$areaFuncaoHistorico->area_id = $colaborador->area_id;
						$areaFuncaoHistorico->data = date("Y-m-d");
						$areaFuncaoHistorico->hora = date("H:i:s");
						$areaFuncaoHistorico->usuario_id = $session_dados->id;
						$areaFuncaoHistorico->del = 0;
						$this->getAreaFuncaoHistoricoTable()->saveAreaFuncaoHistorico($areaFuncaoHistorico);
					}
				}



				//Salva histórico representante
				if ($representante_id2 != $colaborador->representante_id) {
					$usuario_id = $id;
					$representante_id = $dados_form['representante_id'];
					$usuario_update = $session_dados['id'];
					if ($reprensentante_id_banco != $representante_id) {
						$this->getUsuarioTable()->saveRepresentanteUsuario($usuario_id, $representante_id, $usuario_update);
					}
				}



				/* INICIO Grava log */
				$log_acao = "colaborador/edit";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Edita colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */
				// die;
				return $this->redirect()->toRoute('colaborador');
			}
		}

		return array(
			'id' => $id,
			'colaborador' => $colaborador,
			'tipo_vinculos' => $this->getTipoVinculoTable()->getTipoVinculosAtivos(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'representantes2' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
			'areas2' => $this->getAreaTable()->getAreasAtivas(),
			'funcoes_por_area' => $funcoes_por_area,
			'bonificacoes' => $this->getBonificacaoTable()->getBonificacoes(),
			'bolsas' => $this->getBolsaTable()->getBolsas(),
			'documentos' => $this->getDocumentoUsuarioTable()->getDocumentosPorUsuario($id),
			'projetos' => $projetos,
			'projetos1' => $projetos,
		);
	}


	public function uploadDocumentoAction()
	{
		$session_dados = new Container('usuario_dados');
		$id = (int) $this->params()->fromRoute('id', 0);
		$colaborador = $this->getUsuarioTable()->getUsuario($id);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$documento = new DocumentoUsuario();
			$dados_form = $request->getPost();

			if ($dados_form) {


				$documento->tipo = $dados_form['tipo'];
				$documento->usuario_id = $colaborador->id;
				$documento->usuario_upload_id = $session_dados->id;
				$documento->data = date("Y-m-d");
				$documento->hora = date("H:i:s");

				//FAZ UPLOAD DO ANEXO
				/* Diretorio Local */
				$sistema = "convenio22";
				//$sistema = "convenio_producao_28_12_17";
				$diretorio = __DIR__;
				$diretorio = str_replace("\\", '/', $diretorio);
				$diretorio = explode($sistema, $diretorio);
				/* Diretorio Local */
				//echo $diretorio[0].$sistema; die;				
				/* Diretorio Local */



				$file = $request->getFiles()->toArray();
				if ($_FILES['arquivo']['error'] == 0) {
					// unlink($sistema . '/public/colaborador-anexo1/' . $colaborador->arquivo);
					$ext = strtolower(end(explode(".", $file['arquivo']['name'])));
					$documento->arquivo = md5(time()) . '.' . $ext;
					$target_file = $diretorio[0] . $sistema . '/public/colaborador-anexo/' . $documento->arquivo;


					if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) {
						$this->getDocumentoUsuarioTable()->saveDocumento($documento);
					} else {
						echo "erro";
					}


					/* INICIO Grava log */
					$log_acao = "colaborador/upload-documento";
					$log_acao_id = $id;
					$log_acao_exibicao = 'Upload de documento colaborador';
					$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
					/* FIM Grava log */
					// die;

				}

				if ($session_dados['permissao'] == 18) {
					return $this->redirect()->toRoute('colaborador/uniselva-view', array('id' => $id));
				} else {
					return $this->redirect()->toRoute('colaborador/edit', array('id' => $id));
				}
			}
		}

		return array(
			'id' => $id,
			'colaborador' => $colaborador,
			'tipo_vinculos' => $this->getTipoVinculoTable()->getTipoVinculosAtivos(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'representantes2' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
			'areas2' => $this->getAreaTable()->getAreasAtivas(),
			'funcoes_por_area' => $funcoes_por_area,
			'bonificacoes' => $this->getBonificacaoTable()->getBonificacoes(),
		);
	}


	public function salvarRepresentanteUsuario($usuario_id, $representante_id)
	{
		date_default_timezone_set('America/Cuiaba');
		$session_dados = new Container('usuario_dados');
		$representante_usuario = new RepresentanteUsuario();
		$representante_usuario->usuario_id = $usuario_id;
		$representante_usuario->representante_id = $representante_id;
		$representante_usuario->data = date('Y-m-d H:i:s');
		$representante_usuario->del = 0;
		$this->getRepresentanteUsuarioTable()->saveRepresentanteUsuario($representante_usuario);
	}




	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('colaborador');
		}

		if ($id) {

			$this->getUsuarioTable()->deleteUsuario($id);
			$this->getUsuarioProjetoTable()->deleteUsuarioProjetosByUsuario($id);

			/* INICIO Grava log */
			$log_acao = "colaborador/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui colaborador';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('colaborador');
		}

		return array(
			'id'    => $id,
			'colaborador' => $this->getUsuarioTable()->getUsuario($id)
		);
	}


	public function deleteDocumentoAction()
	{
		$session_dados = new Container('usuario_dados');

		echo $id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('colaborador');
		}

		if ($id) {
			$documento = $this->getDocumentoUsuarioTable()->getDocumento($id);
			$this->getDocumentoUsuarioTable()->deleteDocumento($id);
			unlink('/var/www/html/convenio22/public/colaborador-anexo/' . $documento->arquivo);

			/* INICIO Grava log */
			$log_acao = "colaborador/delete-documento";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui documento colaborador';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			// die;
			if ($session_dados['permissao'] == 18) {
				return $this->redirect()->toRoute('colaborador/uniselva-view', array('id' => $_GET['uid']));
			} else {
				return $this->redirect()->toRoute('colaborador/edit', array('id' => $_GET['uid']));
			}
		}
	}

	public function exportarAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		if ($_GET['status'] != "") {
			$status_busca = $_GET['status'];
		}
		if ($_GET['tipo_vinculo'] != "") {
			$tipo_vinculo_busca = $_GET['tipo_vinculo'];
		}

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByUsuariosAtivos($status_busca, $tipo_vinculo_busca);

		$usuarios_atividades = $this->getUsuarioAtividadeTable()->getUsuarioLastAtividades();
		$usuarios_atividades_order = array();

		foreach ($usuarios_atividades as $usuario_atividade) {

			$usuarios_atividades_order[$usuario_atividade['usuario_id']][$usuario_atividade['projeto_id']][] = $usuario_atividade;
		}


		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();

		foreach ($representantes_bd as $representante) {
			$representantes[$representante->id] = $representante->nome;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();

		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto->id] = ($projeto->descricao);
		}

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();

		foreach ($areas_bd as $area) {
			$areas[$area->id] = $area->descricao;
		}

		$vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$vinculos = array();

		foreach ($vinculos_bd as $vinculo) {
			$vinculos[$vinculo->id] = $vinculo->descricao;
		}




		/* INICIO Grava log */
		$log_acao = "colaborador/exportar";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Exporta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$viewModel->setVariables(array(
			'areas' => $areas,
			'projetos' => $projetos,
			'usuario_projetos' => $usuario_projetos_bd,
			'representantes' => $representantes,
			'usuarios_atividades' => $usuarios_atividades_order,
			'vinculos' => $vinculos
		));

		return $viewModel;
	}


	public function exportar1Action()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();
		foreach ($areas_bd as $area) {
			$areas[$area->id] = $area->descricao;
		}

		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();
		foreach ($representantes_bd as $representante) {
			$representantes[$representante->id] = $representante->nome;
		}


		$tipo_vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$tipo_vinculos = array();
		foreach ($tipo_vinculos_bd as $tipo_vinculo) {
			$tipo_vinculos[$tipo_vinculo->id] = $tipo_vinculo->descricao;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();
		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto->id] = $projeto->descricao;
		}



		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByColaborador();
		$colaborador_representantes = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$colaborador_representantes[$usuario_projeto['usuario_id']][] = $usuario_projeto['representante_id'];
			$colaborador_representantes[$usuario_projeto['usuario_id']] = array_unique($colaborador_representantes[$usuario_projeto['usuario_id']]);

			$colaborador_projetos[$usuario_projeto['usuario_id']][] = $usuario_projeto['projeto_id'];
			$colaborador_projetos[$usuario_projeto['usuario_id']] = array_unique($colaborador_projetos[$usuario_projeto['usuario_id']]);

			//$colaborador_projetos1[$usuario_projeto['usuario_id']] = $usuario_projeto['descricao'];
		}

		if ($_GET['status'] != "") {
			$status_busca = $_GET['status'];
		}
		if ($_GET['meta'] != "") {
			$meta = $_GET['meta'];
		}
		if ($_GET['tipo_vinculo'] != "") {
			$tipo_vinculo_busca = $_GET['tipo_vinculo'];
		}

		$usuarios_atividades = $this->getUsuarioAtividadeTable()->getUsuarioLastAtividades();
		$usuarios_atividades_order = array();

		foreach ($usuarios_atividades as $usuario_atividade) {

			$usuarios_atividades_order[$usuario_atividade['usuario_id']][$usuario_atividade['projeto_id']][] = $usuario_atividade;
		}

		/* INICIO Grava log */
		$log_acao = "colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$viewModel->setVariables(array(
			'colaboradores' => $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca, $meta),
			'areas' => $areas,
			'tipo_vinculos' => $tipo_vinculos,
			'colaborador_representantes' => $colaborador_representantes,
			'representantes' => $representantes,
			'projetos' => $projetos,
			'colaborador_projetos' => $colaborador_projetos,
			'usuarios_atividades' => $usuarios_atividades_order,
			'tipo_vinculos_bd' => $this->getTipoVinculoTable()->fetchAll(),
		));

		return $viewModel;
	}



	public function exportar2Action()
	{

		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();
		foreach ($areas_bd as $area) {
			$areas[$area->id] = $area->descricao;
		}

		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();
		foreach ($representantes_bd as $representante) {
			$representantes[$representante->id] = $representante->nome;
		}


		$tipo_vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$tipo_vinculos = array();
		foreach ($tipo_vinculos_bd as $tipo_vinculo) {
			$tipo_vinculos[$tipo_vinculo->id] = $tipo_vinculo->descricao;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();
		foreach ($projetos_bd as $projeto) {
			$projetos[$projeto->id] = $projeto->descricao;
		}



		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByColaborador();
		$colaborador_representantes = array();

		foreach ($usuario_projetos_bd as $usuario_projeto) {
			$colaborador_representantes[$usuario_projeto['usuario_id']][] = $usuario_projeto['representante_id'];
			$colaborador_representantes[$usuario_projeto['usuario_id']] = array_unique($colaborador_representantes[$usuario_projeto['usuario_id']]);

			$colaborador_projetos[$usuario_projeto['usuario_id']][] = $usuario_projeto['projeto_id'];
			$colaborador_projetos[$usuario_projeto['usuario_id']] = array_unique($colaborador_projetos[$usuario_projeto['usuario_id']]);

			//$colaborador_projetos1[$usuario_projeto['usuario_id']] = $usuario_projeto['descricao'];
		}

		if ($_GET['status'] != "") {
			$status_busca = $_GET['status'];
		}
		if ($_GET['meta'] != "") {
			$meta = $_GET['meta'];
		}
		if ($_GET['tipo_vinculo'] != "") {
			$tipo_vinculo_busca = $_GET['tipo_vinculo'];
		}

		$usuarios_atividades = $this->getUsuarioAtividadeTable()->getUsuarioLastAtividades();
		$usuarios_atividades_order = array();

		foreach ($usuarios_atividades as $usuario_atividade) {

			$usuarios_atividades_order[$usuario_atividade['usuario_id']][$usuario_atividade['projeto_id']][] = $usuario_atividade;
		}

		/* INICIO Grava log */
		$log_acao = "colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		$viewModel->setVariables(array(
			'colaboradores' => $this->getUsuarioTable()->getUsuariosColaboradores1($status_busca, $tipo_vinculo_busca, $meta),
			'areas' => $areas,
			'tipo_vinculos' => $tipo_vinculos,
			'colaborador_representantes' => $colaborador_representantes,
			'representantes' => $representantes,
			'projetos' => $projetos,
			'colaborador_projetos' => $colaborador_projetos,
			'usuarios_atividades' => $usuarios_atividades_order,
			'tipo_vinculos_bd' => $this->getTipoVinculoTable()->fetchAll(),
		));

		return $viewModel;
	}

	public function getProjetosAction()
	{
		$request = $this->getRequest();
		$response = $this->getResponse();

		if ($request->isPost()) {

			$response->setStatusCode(200);
			$area_id = $request->getPost('area');

			$data = $this->getProjetoTable()->getProjetosAtivosbyArea($area_id);

			//$buffer = '<select  class="form-control"  name="submeta_id" id="submeta_id" required>';
			$buffer = "";

			foreach ($data as $prov) {
				$buffer .= "<option value='" . $prov['id'] . "'>" . ($prov['descricao']) . "</option>";
			}

			//$buffer .= '</select>';

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

	public function getFuncoesAction()
	{
		$request = $this->getRequest();
		$response = $this->getResponse();

		if ($request->isPost()) {

			$response->setStatusCode(200);
			$area_id = $request->getPost('area');


			// $data = $this->getSubmetaTable()->getSubmetasAtivasporArea($area_id);
			$data = $this->getAreaFuncaoTable()->getAreaFuncoes($area_id);

			//$buffer = '<select  class="form-control"  name="submeta_id" id="submeta_id" required>';
			$buffer = "<option value=''>Selecione uma função </option>";

			foreach ($data as $prov) {
				$buffer .= "<option value='" . $prov['id'] . "'>" . ($prov['funcao']) . "</option>";
			}

			//$buffer .= '</select>'; 

			$response->setContent($buffer);
			$headers = $response->getHeaders();
		}

		return $response;
	}
}
