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

use Application\Model\Escolaridade;
use Application\Model\EscolaridadeTable;

use Application\Model\RepresentanteTable;
use Application\Model\Representante;

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;

use Application\Model\Participante;
use Application\Model\ParticipanteTable;

use Application\Model\ParticipanteProjeto;
use Application\Model\ParticipanteProjetoTable;

use Application\Model\LogTable;
use Application\Model\Log;

class ParticipanteController extends AbstractActionController
{
    protected $usuarioTable;
    protected $usuarioProjetoTable;
    protected $areaTable;
    protected $tipoVinculoTable;
    protected $escolaridadeTable;
    protected $representanteTable;
    protected $permissaoTable;
    protected $projetoTable;
    protected $usuarioAtividadeTable;
    protected $participanteTable;
	protected $participanteProjetoTable;
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

	public function getEscolaridadeTable()
	{
		if (!$this->escolaridadeTable) {
			$sm = $this->getServiceLocator();
			$this->escolaridadeTable = $sm->get('Application\Model\EscolaridadeTable');
		}
		return $this->escolaridadeTable;
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

	public function getParticipanteTable()
	{
		if (!$this->participanteTable) {
			$sm = $this->getServiceLocator();
			$this->participanteTable = $sm->get('Application\Model\ParticipanteTable');
		}
		return $this->participanteTable;
	}

	public function getParticipanteProjetoTable()
	{
		if (!$this->participanteProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->participanteProjetoTable = $sm->get('Application\Model\ParticipanteProjetoTable');
		}
		return $this->participanteProjetoTable;
	}

    public function indexAction()
    {
    	/*
    	 $usuarios = $this->getUsuarioTable()->fetchAll();

    	 foreach ($usuarios as $usuario){
	    	 $usuario->nome = utf8_decode($usuario->nome);
	    	 $usuario->razao_social = utf8_decode($usuario->razao_social);
	    	 $usuario->instituicao = utf8_decode($usuario->instituicao);
	    	 $usuario->cargo_funcao = utf8_decode($usuario->cargo_funcao);
	    	 $usuario->unidade_lotacao = utf8_decode($usuario->unidade_lotacao);

    	 	//$this->getUsuarioTable()->saveUsuario($usuario);
    	 }
    	 die;
    	 */

    	$areas_bd = $this->getAreaTable()->fetchAll();
    	$areas = array();

    	foreach ($areas_bd as $area){
    		$areas[$area->id] = $area->descricao;
    	}

    	$representantes_bd = $this->getRepresentanteTable()->fetchAll();
    	$representantes = array();

    	foreach ($representantes_bd as $representante){
    		$representantes[$representante->id] = $representante->nome;
    	}

    	$tipo_vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
    	$tipo_vinculos = array();

    	foreach ($tipo_vinculos_bd as $tipo_vinculo){
    		$tipo_vinculos[$tipo_vinculo->id] = $tipo_vinculo->descricao;
    	}

    	$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByColaborador();

		$colaborador_representantes = array();

    	foreach ($usuario_projetos_bd as $usuario_projeto){
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

      $cpf= $_GET['cpf'];
    	return new ViewModel(array(
    			'participantes' => $this->getParticipanteTable()->getParticipantes($cpf),
    			'areas' => $areas,
    			'tipo_vinculos' => $tipo_vinculos,
    			'colaborador_representantes' => $colaborador_representantes,
    			'representantes' => $representantes,
    			'colaborador_projetos' => $colaborador_projetos,
    	));
    }

	public function addAction()
	{

		//$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		$request = $this->getRequest();
		if($_GET['cpf']){

				$participante = $this->getParticipanteTable()->getParticipanteCpf($dados_form['cpf']);
				//echo $participante['cpf'];die;

				}

		if ($request->isPost()) {
			$participante = new Participante();
			$dados_form = $request->getPost();

			if ($dados_form) {

		$get_participante = $this->getParticipanteTable()->getParticipanteCpf($dados_form['cpf']);
		$total_participante = count($get_participante); 
        if ($total_participante>0){ 
			//$msg = "Beneficiário ja cadastrado: <font color='red'>".$participante->nome." - CPF: ".$participante->cpf." - <a href='../../participante/associar-participante/".$participante->id."'><button>vincular projetos</button></a></font>  <hR />"; } else {
				$msg = "<font color='red'>Beneficiário ja cadastrado</font> "; } else {


				$participante->nome = $dados_form['nome'];
				$participante->email = $dados_form['email'];
				$participante->cpf = $dados_form['cpf'];
				$participante->senha = md5('ufmt2017');
				$participante->tel_fixo = $dados_form['tel_fixo'];
				$participante->tel_movel = $dados_form['tel_movel'];
				if ($dados_form['data_nascimento']!=""){
				$participante->data_nascimento = $dados_form['data_nascimento'];
				}
				if ($dados_form['nivel_escolaridade']!=""){
				$participante->nivel_escolaridade = $dados_form['nivel_escolaridade'];
				}
				$participante->del = 0;
				//print_r($participante); die;
				$participante_id = $this->getParticipanteTable()->saveParticipante($participante);

				///



    			/* INICIO Grava log */
    			$log_acao = "participante/add";
    			$log_acao_id = $usuario_id;
    			$log_acao_exibicao = 'Cadastra participante';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */

				return $this->redirect()->toRoute('participante',  array('action' => 'participante'));
       }
			}
		}

		return new ViewModel(array(
    			'escolaridades' => $this->getEscolaridadeTable()->fetchAll(),
				'tipo_vinculos' => $this->getTipoVinculoTable()->fetchAll(),
    			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
    			'areas' => $this->getAreaTable()->getAreasAtivas(),
				'projeto_id' => $projeto_id,
				'participante' => $participante,
        'msg' => $msg
		));
	}


	public function associarParticipanteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('participante', array(
					'action' => 'index'
			));
		}

		try {
			$participante = $this->getParticipanteTable()->getParticipante($id);
		} catch (Exception $e) {
			return $this->redirect()->toRoute('participante', array(
					'action' => 'index'
			));
		}

    	$participantes_projetos_bd = $this->getParticipanteProjetoTable()->getParticipantesProjetosByUsuario($id);

    	/*$representantes_bd = $this->getRepresentanteTable()->fetchAll();
    	$representantes = array();

    	foreach ($representantes_bd as $representante){
    		$representantes[$representante->id] = $representante->nome;
    	}*/

    	$projetos_bd = $this->getProjetoTable()->fetchAll();
    	$projetos = array();

    	foreach ($projetos_bd as $projeto){
    		$projetos[$projeto->id] = ($projeto->descricao);
    	}

    	$areas_bd = $this->getAreaTable()->fetchAll();
    	$areas = array();

    	foreach ($areas_bd as $area){
    		$areas[$area->id] = $area->descricao;
    	}

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {

				foreach($dados_form['projeto_id'] as $projeto_id) {
					$participante_projeto = new ParticipanteProjeto();
					$participante_projeto->projeto_id = $projeto_id;
					$participante_projeto->usuario_id = $id;
					$participante_projeto->representante_id = 0;
					$participante_projeto->del = 0;
					//print_r($participante_projeto);echo '<br>';
					$participante_projeto_id = $this->getParticipanteProjetoTable()->saveParticipanteProjeto($participante_projeto);

	    			/* INICIO Grava log */
	    			$log_acao = "participante/associar-participante";
	    			$log_acao_id = $usuario_projeto_id;
	    			$log_acao_exibicao = 'Cadastra projeto de participante';
	    			//$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
	    			/* FIM Grava log */
				}

				return $this->redirect()->toRoute('participante/associar-participante', array(
						'action' => 'associar-participante', 'id' => $id
				));
			}
		}

    	/* INICIO Grava log */
    	$log_acao = "associar-participante";
    	$log_acao_id = $usuario_id;
    	$log_acao_exibicao = 'Consulta projetos de participante';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */

		return new ViewModel(array(
				'id' => $id,
				'participante' => $participante,
    			'areas' => $areas,
    			'projetos' => $projetos,
    			'participante_projetos' => $participantes_projetos_bd,
    			//'representantes' => $representantes,
		));
	}

	public function editAction()
	{

		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('participante', array(
					'action' => 'index'
			));
		}


		$participante = $this->getParticipanteTable()->getParticipante($id);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$participante = new Participante();
			$dados_form = $request->getPost();

			if ($dados_form) {
				$participante->id = $id;
				$participante->nome = $dados_form['nome'];
				$participante->email = $dados_form['email'];
				$participante->tel_fixo = $dados_form['tel_fixo'];
				$participante->tel_movel = $dados_form['tel_movel'];
				$participante->cpf = $dados_form['cpf'];
				$participante->data_nascimento = $dados_form['data_nascimento'];
				$participante->nivel_escolaridade = $dados_form['nivel_escolaridade'];
				$participante->del = '0';

				$participante_id = $this->getParticipanteTable()->saveParticipante($participante);

    			/* INICIO Grava log */
    			$log_acao = "participante/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita participante';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */

				return $this->redirect()->toRoute('participante');
			}
		}

		return array(
				'id' => $id,
				'participante' => $this->getParticipanteTable()->getParticipante($id),
    			'escolaridades' => $this->getEscolaridadeTable()->fetchAll(),
    			'permissoes' => $this->getPermissaoTable()->getPermissaosAtivas(),
    			'areas' => $this->getAreaTable()->getAreasAtivas(),
		);
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('participante');
		}

		if ($id) {

			$this->getParticipanteTable()->deleteParticipante($id);
			//$this->getUsuarioProjetoTable()->deleteUsuarioProjetosByUsuario($id);

			/* INICIO Grava log */
			$log_acao = "participante/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui participante';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('participante');
		}

		return array(
				'id'    => $id,
				'participante' => $this->getParticipanteTable()->getParticipante($id)
		);
	}


	public function deleteProjetoParticipanteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);


		if (!$id) {
			return $this->redirect()->toRoute('participante');
		}
		if ($id) {

			$this->getParticipanteProjetoTable()->deleteProjetoParticipante($id);
			//$this->getUsuarioProjetoTable()->deleteUsuarioProjetosByUsuario($id);

			/* INICIO Grava log */
			$log_acao = "participante/delete-projeto-participante";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui projeto do participante';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('participante/associar-participante', array('id' =>$usuario_id));
		}

		return array(
				'id'    => $id,
				'participante' => $this->getParticipanteTable()->getParticipante($usuario_id)
		);
	}

	public function exportarAction()
	{
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);

		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByUsuariosAtivos();

		$usuarios_atividades = $this->getUsuarioAtividadeTable()->getUsuarioLastAtividades();
		$usuarios_atividades_order = array();

		foreach ($usuarios_atividades as $usuario_atividade){

			$usuarios_atividades_order[$usuario_atividade['usuario_id']][$usuario_atividade['projeto_id']][] = $usuario_atividade;
		}


		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
		$representantes = array();

		foreach ($representantes_bd as $representante){
			$representantes[$representante->id] = $representante->nome;
		}

		$projetos_bd = $this->getProjetoTable()->fetchAll();
		$projetos = array();

		foreach ($projetos_bd as $projeto){
			$projetos[$projeto->id] = ($projeto->descricao);
		}

		$areas_bd = $this->getAreaTable()->fetchAll();
		$areas = array();

		foreach ($areas_bd as $area){
			$areas[$area->id] = $area->descricao;
		}

		$vinculos_bd = $this->getTipoVinculoTable()->fetchAll();
		$vinculos = array();

		foreach ($vinculos_bd as $vinculo){
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
				$buffer.= "<option value='".$prov['id']."'>".($prov['descricao'])."</option>";
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
