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

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;

use Application\Model\LogTable;
use Application\Model\Log;

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
	protected $logTable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getUsuarioAtividadeTable()
	{
		if (!$this->usuarioAtividadeTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioAtividadeTable = $sm->get('Application\Model\UsuarioAtividadeTable');
		}
		return $this->usuarioAtividadeTable;
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

		/* INICIO Grava log */
		$log_acao = "colaborador";
		$log_acao_id = NULL;
		$log_acao_exibicao = 'Consulta colaboradores';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'colaboradores' => $this->getUsuarioTable()->getUsuariosColaboradores(),
			'areas' => $areas,
			'tipo_vinculos' => $tipo_vinculos,
			'colaborador_representantes' => $colaborador_representantes,
			'representantes' => $representantes,
			'colaborador_projetos' => $colaborador_projetos,
		));
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
				$colaborador->cpf = $dados_form->cpf;
				$colaborador->razao_social = $dados_form['razao_social'];
				$colaborador->cnpj = $dados_form['cnpj'];
				$colaborador->area_id = $dados_form['area_id'];
				$colaborador->vinculo = $dados_form['vinculo'];
				$colaborador->instituicao = $dados_form['instituicao'];
				$colaborador->curso = $dados_form['curso'];
				$colaborador->polo = $dados_form['polo'];
				$colaborador->tipo_processo_seletivo = $dados_form['tipo_processo_seletivo'];
				$colaborador->nu_edital = $dados_form['nu_edital'];
				$colaborador->cargo_funcao = $dados_form['cargo_funcao'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->unidade_lotacao = $dados_form['unidade_lotacao'];
				$colaborador->status_enquadramento_funcional = $dados_form['status_enquadramento_funcional'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->permissao = $dados_form['permissao'];
				$colaborador->data_admissao = date('Y-m-d', strtotime($dados_form['data_admissao']));
				$colaborador->del = 0;
				$colaborador->atualizado = 0;
				$colaborador->data_atualizado = 0;
				$colaborador->hora_atualizado = 0;
				$colaborador->status = 'ativo';
				$colaborador->data_inatividade = 0;
				$colaborador->superadmin = 0;
				if ($dados_form['representante_id']) {
					$colaborador->representante_id = $dados_form['representante_id'];
				}

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
			'tipo_vinculos' => $this->getTipoVinculoTable()->fetchAll(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
		));
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('colaborador', array(
				'action' => 'index'
			));
		}

		try {
			$colaborador = $this->getUsuarioTable()->getUsuario($id);
		} catch (\Exception $ex) {
			return $this->redirect()->toRoute('colaborador', array(
				'action' => 'index'
			));
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {

				// echo '<pre>';
				// print_r($dados_form);
				// echo '</pre>';
				// die;

				$colaborador->nome = $dados_form['nome'];
				$i = explode(" ", $colaborador->nome);
				$sobrenome = $i[1];
				$colaborador->email = $dados_form['email'];
				$colaborador->tel_fixo = $dados_form['tel_fixo'];
				$colaborador->tel_movel = $dados_form['tel_movel'];
				$colaborador->cpf = $dados_form->cpf;
				$colaborador->razao_social = $dados_form['razao_social'];
				$colaborador->cnpj = $dados_form['cnpj'];
				$colaborador->area_id = $dados_form['area_id'];
				$colaborador->vinculo = $dados_form['vinculo'];
				$colaborador->instituicao = $dados_form['instituicao'];

				$colaborador->curso = $dados_form['curso'];
				$colaborador->polo = $dados_form['polo'];
				$colaborador->tipo_processo_seletivo = $dados_form['tipo_processo_seletivo'];
				$colaborador->nu_edital = $dados_form['nu_edital'];

				$colaborador->cargo_funcao = $dados_form['cargo_funcao'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->unidade_lotacao = $dados_form['unidade_lotacao'];
				$colaborador->status_enquadramento_funcional = $dados_form['status_enquadramento_funcional'];
				$colaborador->funcao = $dados_form['funcao'];
				$colaborador->permissao = $dados_form['permissao'];
				$colaborador->data_admissao = date('Y-m-d', strtotime($dados_form['data_admissao']));
				$colaborador->status = $dados_form['status'];
				$colaborador->data_inatividade = date('Y-m-d', strtotime($dados_form['data_inativadade']));
				$colaborador->representante_id = $dados_form['representante_id'];
				$colaborador->is_representante = $dados_form['is_representante'];


				$colaborador->atualizado = '1';
				$colaborador->superadmin = '0';


				//FAZ UPLOAD DO ANEXO
				/* Diretorio Local */
				$sistema = "uab-convenio";

				$diretorio = __DIR__;
				$diretorio = str_replace("\\", '/', $diretorio);
				$diretorio = explode($sistema, $diretorio);
				/* Diretorio Local */
				// echo $diretorio[0].$sistema; die;				
				/* Diretorio Local */

				$file = $request->getFiles()->toArray();

				// print_r($file);
				// die;
				if ($_FILES['arquivo']['error'] == 0) {
					unlink($diretorio[0] . $sistema . '/public/colaborador-anexo/' . $colaborador->arquivo);
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
						$nomeArquivo = $diretorio[0] . $sistema  . '/public/colaborador-anexo/' . $colaborador->arquivo;
						unlink($nomeArquivo);
						$colaborador->arquivo = NULL;
					}
				}

				$this->getUsuarioTable()->saveUsuario($colaborador);
				/*
				foreach($dados_form['projeto_id'] as $projeto_id) {
					$usuario_projeto = new UsuarioProjeto();
					$usuario_projeto->projeto_id = $projeto_id;
					$usuario_projeto->usuario_id = $id;
					$usuario_projeto->representante_id = 0;
					$usuario_projeto->del = 0;
					$this->getUsuarioProjetoTable()->saveUsuarioProjeto($usuario_projeto);
				}*/

				/* INICIO Grava log */
				$log_acao = "colaborador/edit";
				$log_acao_id = $id;
				$log_acao_exibicao = 'Edita colaborador';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('colaborador');
			}
		}

		return array(
			'id' => $id,
			'colaborador' => $colaborador,
			'tipo_vinculos' => $this->getTipoVinculoTable()->fetchAll(),
			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
			'areas' => $this->getAreaTable()->getAreasAtivas(),
			'representantes' => $this->getUsuarioTable()->getUsuariosRepresentantes(),
		);
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

	public function exportarAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByUsuariosAtivos();

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
}
