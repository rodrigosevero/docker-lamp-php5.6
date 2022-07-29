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
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Model\TestEntity;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

use Application\Model\Usuario;
use Application\Model\UsuarioTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\TipoProjeto;
use Application\Model\TipoProjetoTable;

use Application\Model\Representante;
use Application\Model\RepresentanteTable;

use Application\Model\AtividadeProjeto;
use Application\Model\AtividadeProjetoTable;

use Application\Model\AtividadeProjetoUsuario;
use Application\Model\AtividadeProjetoUsuarioTable;


use Application\Model\UsuarioProjeto;
use Application\Model\UsuarioProjetoTable;

use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;

use Application\Model\RepresentanteProjeto;
use Application\Model\RepresentanteProjetoTable;

use Application\Model\Participante;
use Application\Model\ParticipanteTable;

use Application\Model\ParticipanteProjeto;
use Application\Model\ParticipanteProjetoTable;


use Zend\View\Model\JsonModel;

use Application\Model\LogTable;
use Application\Model\Log;

class ProjetoController extends AbstractActionController
{
    protected $usuarioTable;
    protected $projetoTable;
    protected $tipoProjetoTable;
    protected $representanteTable;
    protected $areaTable;
    protected $nucleoTable;
    protected $representanteProjetoTable;
    protected $atividadeProjetoTable;
    protected $atividadeProjetoUsuarioTable;
    protected $usuarioProjetoTable;
	protected $usuarioAtividadeTable;
    protected $logTable;
    protected $participanteTable;
	protected $participanteProjetoTable;


	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

    public function getAtividadeProjetoTable()
    {
    	if (!$this->atividadeProjetoTable) {
    		$sm = $this->getServiceLocator();
    		$this->atividadeProjetoTable = $sm->get('Application\Model\AtividadeProjetoTable');
    	}
    	return $this->atividadeProjetoTable;
    }

	public function getUsuarioAtividadeTable()
    {
    	if (!$this->usuarioAtividadeTable) {
    		$sm = $this->getServiceLocator();
    		$this->usuarioAtividadeTable = $sm->get('Application\Model\UsuarioAtividadeTable');
    	}
    	return $this->usuarioAtividadeTable;
    }

	public function getAtividadeProjetoUsuarioTable()
    {
    	if (!$this->atividadeProjetoUsuarioTable) {
    		$sm = $this->getServiceLocator();
    		$this->atividadeProjetoUsuarioTable = $sm->get('Application\Model\AtividadeProjetoUsuarioTable');
    	}
    	return $this->atividadeProjetoUsuarioTable;
    }

	public function getRepresentanteProjetoTable()
	{
		if (!$this->representanteProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->representanteProjetoTable = $sm->get('Application\Model\RepresentanteProjetoTable');
		}
		return $this->representanteProjetoTable;
	}

	public function getNucleoTable()
	{
		if (!$this->nucleoTable) {
			$sm = $this->getServiceLocator();
			$this->nucleoTable = $sm->get('Application\Model\NucleoTable');
		}
		return $this->nucleoTable;
	}

	public function getAreaTable()
	{
		if (!$this->areaTable) {
			$sm = $this->getServiceLocator();
			$this->areaTable = $sm->get('Application\Model\AreaTable');
		}
		return $this->areaTable;
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

	public function getProjetoTable()
	{
		if (!$this->projetoTable) {
			$sm = $this->getServiceLocator();
			$this->projetoTable = $sm->get('Application\Model\ProjetoTable');
		}
		return $this->projetoTable;
	}

	public function getTipoProjetoTable()
	{
		if (!$this->tipoProjetoTable) {
			$sm = $this->getServiceLocator();
			$this->tipoProjetoTable = $sm->get('Application\Model\TipoProjetoTable');
		}
		return $this->tipoProjetoTable;
	}

    public function getUsuarioProjetoTable()
    {
    	if (!$this->usuarioProjetoTable) {
    		$sm = $this->getServiceLocator();
    		$this->usuarioProjetoTable = $sm->get('Application\Model\UsuarioProjetoTable');
    	}
    	return $this->usuarioProjetoTable;
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

      $usuario_dados = new Container('usuario_dados');

    	$projetos1 = $this->getProjetoTable()->getProjetosAtivos();
    	$projetos_bd = $this->getProjetoTable()->getProjetosAtivosDiferenteMatriz();
    	$projetos_matriz_bd = $this->getProjetoTable()->getProjetosByTipoProjeto(9);
		
    	$areas_bd = $this->getAreaTable()->fetchAll();
    	$areas = array();
    	foreach ($areas_bd as $area){
    		$areas[$area->id] = $area->descricao;
    	}
		
        $tipo_projeto_bd = $this->getTipoProjetoTable()->fetchAll();
        $tipos = array();
        foreach ($tipo_projeto_bd as $tipo){
            $tipos[$tipo->id] = $tipo->descricao;
        }

    	$usuarios_bd = $this->getUsuarioTable()->fetchAll();
    	$usuarios = array();
    	foreach ($usuarios_bd as $usuario){
    		$usuarios[$usuario->id] = $usuario->nome;
    	}
		
    	$nucleos_bd = $this->getNucleoTable()->fetchAll();
    	$nucleos = array();

    	foreach ($nucleos_bd as $nucleo){
    		$nucleos[$nucleo->id] = $nucleo->descricao;
    	}

		/********************************/
		
		$representantes_bd = $this->getRepresentanteTable()->fetchAll();
    	$representantes = array();
    	foreach ($representantes_bd as $representante){
    		$representantes[$representante->id] = $representante->nome;
    	}
		
    	$representante_projetos_bd = $this->getRepresentanteProjetoTable()->fetchAll();
    	$representante_projetos = array();
    	foreach ($representante_projetos_bd as $representante_projeto){
    		$representante_projetos[$representante_projeto->projeto_id][] = $representante_projeto->representante_id;
    	}

		


    	$atividades_projetos = array();
    	$usuarios_projetos = array();
		$participantes_projetos = array();
		$participantes_projetos_filhos = array();
		$projetos_filhos = array();
    	foreach ($projetos_bd as $projeto){
    		//echo '<pre>';
    		//print_r($projeto);
    		$atividades_projetos[$projeto['id']] = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto['id']);
    		$usuarios_projetos[$projeto['id']] = $this->getUsuarioProjetoTable()->getUsuariosProjetosAtivosByProjeto($projeto['id']);
			$participantes_projetos[$projeto['id']] = $this->getParticipanteProjetoTable()->getParticipantesProjetosAtivosByProjeto($projeto['id']);
			$projetos_filhos[$projeto['id']] = $this->getProjetoTable()->getProjetosAtivosFilho($projeto['id']);
			$participantes_projetos_filhos[$projeto['id']] = $this->getParticipanteProjetoTable()->getParticipantesProjetosAtivosByProjeto($projeto['id']);
    	}
		
		/*
		$participantes_bd = $this->getParticipanteProjetoTable()->fetchAll();
		$participantes = array();
    	foreach ($participantes_bd as $participante){
    		//echo '<pre>';
    		//print_r($projeto);
			$participantes_projeto[$participante['id']] = $this->getProjetoTable()->getProjetosAtivosFilho($projeto['id']);
    	}
		*/

    	foreach ($projetos_matriz_bd as $projeto_m){
    		//echo '<pre>';
    		//print_r($projeto);
    		$atividades_projetos[$projeto_m['id']] = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto_m['id']);
    		$usuarios_projetos[$projeto_m['id']] = $this->getUsuarioProjetoTable()->getUsuariosProjetosAtivosByProjeto($projeto_m['id']);
    	}


		
        if($usuario_dados->permissao==12){
            $projetos_matriz = $this->getProjetoTable()->getProjetosByTipoProjetoByArea(9, $usuario_dados->area_id);
        } else if($usuario_dados->permissao==4){
            $projetos_matriz = $this->getProjetoTable()->getProjetosByTipoProjetoByCoordenadorNucleo(9, $usuario_dados->area_id, $usuario_dados->nucleo);
        }
        else {
            $projetos_matriz = $this->getProjetoTable()->getProjetosByTipoProjeto(9);
        }

        if($usuario_dados->permissao==12){
            $projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatrizByArea($usuario_dados->area_id);
        } else if($usuario_dados->permissao==4){
            $projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatrizByCoordenadorNucleo($usuario_dados->area_id, $usuario_dados->nucleo);
        }
        else {
            $projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatriz();
        }
		
    	/* INICIO Grava log */
    	$log_acao = "projeto";
    	$log_acao_id = NULL;
    	$log_acao_exibicao = 'Consulta projetos';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */

    	return new ViewModel(array(
    		'projetos' => $projetos,
			  'filhos' => $projetos_filhos,
    		'total_projetos_inativos' => count($this->getProjetoTable()->getProjetosInativos()),
    		'areas'	=> $areas,
    		'nucleos'	=> $nucleos,
    		'tipos' => $tipos,
    		'usuarios' => $usuarios,
    		'representantes' => $representantes,
    		'projetos_representantes' => $representante_projetos,
			  'projetos_filhos_geral' => $projetos_filhos_geral,
			  'projetos_filhos' => $projetos_filhos,
    		'projetos_matriz_responsabilidade' => $projetos_matriz,
    		'atividades_projetos' => $atividades_projetos,
    		'usuarios_projetos' => $usuarios_projetos,
			  'participantes_projetos' => $participantes_projetos,
			  'participantes_projetos_filhos' => $participantes_projetos_filhos,
    	));
    }


	public function verColaboradoresAction()
	{


		$dados_sessao_atual = new Container('usuario_dados');
    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
    	}


		$id = (int) $this->params()->fromRoute('id', 0);
		$usuariosporprojeto = $this->getUsuarioProjetoTable()->getColaboradorPorProjeto($id);
		$projeto = $this->getProjetoTable()->getProjeto($id);


		return new ViewModel(array(
    	'usuariosporprojeto' => $usuariosporprojeto,
		'projeto' => $projeto,
		'usuario_dados'=>$dados_sessao_atual
    	));

	}


	public function verParticipantesAction()
	{


		$dados_sessao_atual = new Container('usuario_dados');
    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
		}
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$dados_form = $request->getPost();
			if ($dados_form) {
				foreach($dados_form['concluido'] as $id){
					
					$this->getParticipanteProjetoTable()->updateProjetoParticipanteStatus($id, $dados_form['status']);
					
					// echo $id;
					// echo '<br>';

				}
			}
		}



		$id = (int) $this->params()->fromRoute('id', 0);
		$participantesporprojeto = $this->getParticipanteProjetoTable()->getParticipantesPorProjeto($id);
		$projeto = $this->getProjetoTable()->getProjeto($id);



		return new ViewModel(array(
    	'participantesporprojeto' => $participantesporprojeto,
		'projeto' => $projeto,
		'id' => $id,
    	));

	}

	public function associaratividadesProjetoColaboradorAction()
	{


		$dados_sessao_atual = new Container('usuario_dados');
    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
    	}


		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);
		$usuario = $this->getUsuarioTable()->getUsuario($usuario_id);
		$atividades = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto_id);


		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {

				$this->getProjetoTable()->deleteAtividadeProjetoUsuario($usuario_id, $projeto_id);

				foreach($dados_form['atividade_id'] as $atividade_id) {


					$atividade_projeto_usuario = new AtividadeProjetoUsuario();
					$atividade_projeto_usuario->atividade_projeto_id = $atividade_id;
					$atividade_projeto_usuario->projeto_id = $projeto_id;
					$atividade_projeto_usuario->usuario_id = $usuario_id;
					$atividade_projeto_usuario->del = 0;
					$this->getProjetoTable()->saveAtividadeProjetoUsuario($atividade_id, $projeto_id, $usuario_id);


	    			/* INICIO Grava log */
	    			$log_acao = "projeto/associar-atividades-projeto-colaborador";
	    			$log_acao_id = $usuario_projeto_id;
	    			$log_acao_exibicao = 'Associação de atividade do projeto EPE ao colaborador';
	    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
	    			/* FIM Grava log */
				}

				return $this->redirect()->toRoute('projeto/ver-colaboradores', array(
						'id' => $projeto_id
				));
			}
		}


		$atividades_projeto_usuario_bd1 = $this->getProjetoTable()->getAtividadeProjetoUsuario($usuario_id);
    	$atividades_projeto_usuario1 = array();

    	foreach ($atividades_projeto_usuario_bd1 as $atividades1){
    	$atividades_projeto_usuario1[] = $atividades1['atividade_projeto_id'];
    	}


		return new ViewModel(array(
		'usuario'=>$usuario,
    	'atividades' => $atividades,
		'atividade_projeto_usuario1' => $atividades_projeto_usuario1,
    	));

	}


	public function exportarColaboradoresPorProjetoAction()
	{

		$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);



		$dados_sessao_atual = new Container('usuario_dados');
    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
    	}


		$id = (int) $this->params()->fromRoute('id', 0);
		$usuariosporprojeto = $this->getUsuarioProjetoTable()->getColaboradorPorProjeto($id);
		$projeto = $this->getProjetoTable()->getProjeto($id);


		$viewModel->setVariables(array(
    	'usuariosporprojeto' => $usuariosporprojeto,
		'projeto' => $projeto
    	));

		return $viewModel;

	}



	public function meusRelatoriosAction()
	{
		$dados_sessao_atual = new Container('usuario_dados');



		return new ViewModel(array(

    	));

	}


	public function meusProjetosAction()
    {
    	$dados_sessao_atual = new Container('usuario_dados');
    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
    	}

		

    		$usuario = $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id);

    		$projetos_bd = $this->getProjetoTable()->fetchAll();
    		$projetos = array();

    		foreach ($projetos_bd as $projeto){
    			$projetos[$projeto->id] = ($projeto->descricao);
    		}
		
			$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);
			if ($dados_sessao_atual->permissao == 8){
				// echo $dados_sessao_atual->permissao
				//  print_r($usuario_projetos_bd);
			}
	    	$usuarios_projetos = array();

	    	foreach ($usuario_projetos_bd as $usuario_projeto){
	    		$usuarios_projetos[$usuario_projeto['id']] = $usuario_projeto['projeto_id'];
	    	}
    		if($usuarios_projetos == ""){
				$usuarios_projetos = 0;
			}



    		$usuario = $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id);

    		$projetos_especificos_bd = $this->getProjetoTable()->fetchAll();
    		$projetos_especificos = array();

    		foreach ($projetos_especificos_bd as $projeto_especifico){
    			$projetos_especificos[$projeto_especifico->id] = ($projeto_especifico->descricao);
    		}

	    	$usuario_projetos_especificos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosEspecificosAtivos($dados_sessao_atual->id);
	    	$usuarios_projetos_especificos = array();

	    	foreach ($usuario_projetos_especificos_bd as $usuario_projeto_especifico){
	    		$usuarios_projetos_especificos[$usuario_projeto_especifico['id']] = $usuario_projeto_especifico['projeto_id'];
	    	}


		$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario1($dados_sessao_atual->id);
    	/* INICIO Grava log */
    	$log_acao = "projeto";
    	$log_acao_id = $usuario->id;
    	$log_acao_exibicao = 'Consulta meus projetos';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */

    	return new ViewModel(array(
    			'usuario' => $usuario,
    			'projetos' => $projetos,
				'projetos_especificos' => $projetos_especificos,
          		'usuarios_projetos_especificos' => $usuarios_projetos_especificos,
    			'usuarios_projetos' => $usuarios_projetos,
				'usuario_atividades_meses' => $usuario_atividades_meses,
    	));
    }

    public function inativosAction()
    {
    	$areas_bd = $this->getAreaTable()->fetchAll();
    	$areas = array();

    	foreach ($areas_bd as $area){
    		$areas[$area->id] = $area->descricao;
    	}

    	$usuarios_bd = $this->getUsuarioTable()->fetchAll();
    	$usuarios = array();

    	foreach ($usuarios_bd as $usuario){
    		$usuarios[$usuario->id] = $usuario->nome;
    	}

    	$representantes_bd = $this->getRepresentanteTable()->fetchAll();
    	$representantes = array();

    	foreach ($representantes_bd as $representante){
    		$representantes[$representante->id] = $representante->nome;
    	}

    	$nucleos_bd = $this->getNucleoTable()->fetchAll();
    	$nucleos = array();

    	foreach ($nucleos_bd as $nucleo){
    		$nucleos[$nucleo->id] = $nucleo->descricao;
    	}

    	$representante_projetos_bd = $this->getRepresentanteProjetoTable()->fetchAll();
    	$representante_projetos = array();

    	foreach ($representante_projetos_bd as $representante_projeto){
    		$representante_projetos[$representante_projeto->projeto_id][] = $representante_projeto->representante_id;
    	}

    	return new ViewModel(array(
    			'projetos' => $this->getProjetoTable()->getProjetosInativos(),
    			'areas'	=> $areas,
    			'nucleos'	=> $nucleos,
    			'usuarios' => $usuarios,
    			'representantes' => $representantes,
    			'projetos_representantes' => $representante_projetos,
    			'projetos_matriz_responsabilidade' => $this->getProjetoTable()->getProjetosInativosByTipoProjeto(9)
    	));
    }

    public function addAction()
    {
    	$request = $this->getRequest();
		$sessao = new Container('usuario_dados');

    	if ($request->isPost()) {
    		$projeto = new Projeto();
    		$dados_form = $request->getPost();

    		if ($dados_form) {

    			$file = $request->getFiles()->toArray();

				if(isset($dados_form['pai']) && $dados_form['pai'] != NULL ){
				$projeto->pai = $dados_form['pai'];
				} else {
				    $projeto->pai= 0;
                }
    			$projeto->coordenador_tce_mpc = $dados_form['coordenador_tce_mpc'];
				$projeto->data_inicio = date('Y-m-d', strtotime($dados_form['data_inicio']));
				$projeto->data_fim = date('Y-m-d', strtotime($dados_form['data_fim']));
				if(isset($dados_form['carga_horaria']) && $dados_form['carga_horaria'] != NULL ){
    			$projeto->carga_horaria = $dados_form['carga_horaria'];
    			}
				if(isset($dados_form['vagas_ofertadas']) && $dados_form['vagas_ofertadas'] != NULL ){
				$projeto->vagas_ofertadas = $dados_form['vagas_ofertadas'];
				}
				if(isset($dados_form['entidade_certificadora ']) && $dados_form['entidade_certificadora '] != NULL ){
				$projeto->entidade_certificadora = $dados_form['entidade_certificadora '];
				}
				if(isset($dados_form['status']) && $dados_form['status'] != NULL ){
				$projeto->status = $dados_form['status'];
				}

				$projeto->descricao = $dados_form['descricao'];
    			$projeto->tipo_projeto = $dados_form['tipo_projeto'];
    			$projeto->area_id = $dados_form['area_id'];
    			$projeto->nucleo_id = $dados_form['nucleo_id'];


				if ($dados_form['coordenador_id']==""){ $projeto->coordenador_id = 0; } else { $projeto->coordenador_id = $dados_form['coordenador_id']; }

    			$projeto->representante_tce_id = !isset($dados_form['representante_tce_id']) ?  '0' : $dados_form['representante_tce_id'];

    			$projeto->usuario_id = $sessao->id;
    			$projeto->data = date('Y-m-d');
				date_default_timezone_set('America/Cuiaba');
    			$projeto->hora = date('H:i:s');
    			$projeto->inativo = 0;
    			$projeto->del = 0;
				$projeto->projeto_especial = $dados_form['projeto_especial'];
                if ($_FILES['arquivo']['error'] == 0) {

                    $ext = explode('.', $file['arquivo']['name']);
                    $ext = $ext[1];
                    $projeto->arquivo = md5(time()).'.'.$ext;

                    /* Diretorio Local */
                    $sistema = "convenio21";
                    //$sistema = "convenio_producao_28_12_17";
                    $diretorio = __DIR__;
                    $diretorio = str_replace("\\", '/', $diretorio);
                    $diretorio = explode($sistema, $diretorio);
                    /* Diretorio Local */

                    $target_file = $diretorio[0].$sistema.'/public/projetos/'.$projeto->arquivo;

                    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) { echo "ok";} else { echo "erro"; }
                }

    			$id = $this->getProjetoTable()->saveProjeto($projeto);



		    	/* INICIO Grava log */
		    	$log_acao = "projeto/add";
		    	$log_acao_id = $id;
		    	$log_acao_exibicao = 'Cadastra projeto';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */

    			return $this->redirect()->toRoute('projeto');
    		}
    	}

    	return new ViewModel(array(
    			'usuarios' => $this->getUsuarioTable()->getUsuariosAtivos(),
    			'tipos_projeto' => $this->getTipoProjetoTable()->getTipoProjetosAtivos(),
				'projetospai' => $this->getProjetoTable()->getProjetosPai(),
    			'areas' => $this->getAreaTable()->getAreasAtivas()
    	));
    }

    public function editAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('projeto', array(
					'action' => 'index'
			));
		}

		try {
			$projeto = $this->getProjetoTable()->getProjeto($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('projeto', array(
					'action' => 'index'
			));
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$dados_form = $request->getPost();


			if ($dados_form) {

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



				if ($dados_form['pai']==""){
    			$projeto->pai= 0;
				} else {
				$projeto->pai= $dados_form['pai'];
				}

    			$projeto->descricao = $dados_form['descricao'];
    			$projeto->tipo_projeto = $dados_form['tipo_projeto'];
				if ($dados_form['coordenador_id']==""){
    			$projeto->coordenador_id = 0;
				} else {
				$projeto->coordenador_id = $dados_form['coordenador_id'];
				}



    			if(isset($dados_form['area_id']) && $dados_form['area_id'] != NULL ){
    				$projeto->area_id = $dados_form['area_id'];
    			}



    			if(isset($dados_form['nucleo_id']) && $dados_form['nucleo_id'] != NULL ){
    				$projeto->nucleo_id = $dados_form['nucleo_id'];
    			}

    			$projeto->coordenador_tce_mpc = $dados_form['coordenador_tce_mpc'];

				if(isset($dados_form['data_inicio']) && $dados_form['data_inicio'] != NULL ){
				$projeto->data_inicio = $dados_form['data_inicio'];
				} else {
				$projeto->data_inicio = NULL;
				}

				if(isset($dados_form['data_fim']) && $dados_form['data_fim'] != NULL ){
				$projeto->data_fim = $dados_form['data_fim'];
				} else {
				$projeto->data_fim = NULL;
				}


				if(isset($dados_form['carga_horaria']) && $dados_form['carga_horaria'] != NULL ){
    				$projeto->carga_horaria = $dados_form['carga_horaria'];
    			}
				if(isset($dados_form['vagas_ofertadas']) && $dados_form['vagas_ofertadas'] != NULL ){
				$projeto->vagas_ofertadas = $dados_form['vagas_ofertadas'];
				}
				$projeto->entidade_certificadora = $dados_form['entidade_certificadora'];
				$projeto->status = $dados_form['status'];

    			if(is_array($dados_form['representante_tce_id'])){

    				$representante_projeto = new RepresentanteProjeto();

    				$this->getRepresentanteProjetoTable()->deleteRepresentantesProjeto($id);

    				foreach ($dados_form['representante_tce_id'] as $representante_tce_id){
    					$representante_projeto->projeto_id = $id;
    					$representante_projeto->representante_id = $representante_tce_id;
    					$representante_projeto->del = 0;

    					$this->representanteProjetoTable->saveRepresentanteProjeto($representante_projeto);
    				}
    			}

    			//SABER SE OS CAMPOS ABAIXO SAO ALTERADOS NO EDITAR
    			//$projeto->usuario_id = $sessao->id;
    			//$projeto->data = date('Y-m-d');
    			//date_default_timezone_set('America/Cuiaba');
    			//$projeto->hora = date('H:i:s');
    			$projeto->inativo = 0;
    			$projeto->del = 0;
				if(isset($dados_form['projeto_especial']) && $dados_form['projeto_especial'] != NULL ){
				$projeto->projeto_especial = $dados_form['projeto_especial'];
				} else {
					$projeto->projeto_especial = 0;
				}
                if ($_FILES['arquivo']['error'] == 0) {
                    unlink($sistema.'/public/projetos/'.$projeto->arquivo);
                    $ext = strtolower(end(explode(".", $file['arquivo']['name'])));
                    $projeto->arquivo = md5(time()).'.'.$ext;
					echo $target_file =$diretorio[0].$sistema.'/public/projetos/'.$projeto->arquivo;
					
					

					if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file)) { echo "ok";} 
					else { 
					
						echo "erro"; }
					
                } else {

                    if ($dados_form['delete_anexo']==1){
                        $nomeArquivo = $sistema.'/public/projetos/'.$projeto->arquivo;
                        unlink($nomeArquivo);
                        $projeto->arquivo=NULL;
                    }
				}
				

    			$this->getProjetoTable()->saveProjeto($projeto);

		    	/* INICIO Grava log */
		    	$log_acao = "projeto/edit";
		    	$log_acao_id = $id;
		    	$log_acao_exibicao = 'Edita projeto';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */

    			return $this->redirect()->toRoute('projeto');
    		}
    	}

    	$areas_bd = $this->getAreaTable()->getAreasAtivas();
    	$areas = array();

    	foreach ($areas_bd as $area){
    		$areas[$area['id']] = $area['descricao'];
    	}

    	$nucleos_bd = $this->getNucleoTable()->getNucleosAtivos();
    	$nucleos = array();

    	foreach ($nucleos_bd as $nucleo){
    		$nucleos[$nucleo['id']] = $nucleo['descricao'];
    	}

    	$representantes_projeto_bd = $this->getRepresentanteProjetoTable()->getRepresentantesByProjeto($id);
    	$representantes_projeto = array();

    	foreach ($representantes_projeto_bd as $representante){
    		$representantes_projeto[] = $representante->representante_id;
    	}

    	return new ViewModel(array(
    			'usuarios' => $this->getUsuarioTable()->getUsuariosAtivos(),
    			'tipos_projeto' => $this->getTipoProjetoTable()->getTipoProjetosAtivos(),
    			'areas' => $areas,
    			'nucleos' => $nucleos,
    			'projeto' => $projeto,
				'projetospai' => $this->getProjetoTable()->getProjetosPai(),
    			'representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos(),
    			'representantes_projeto' => $representantes_projeto
    	));
    }

	public function listaCidadeAction()
	{
		$request = $this->getRequest();
		$response = $this->getResponse();

		if ($request->isPost()) {

			$response->setStatusCode(200);
			$area_id = $request->getPost('area');

			$data = $this->getNucleoTable()->getNucleosAtivosporArea($area_id);

			$buffer="<option value=''>Selecione um Núcleo </option>";

			foreach ($data as $prov) {
				$buffer.= "<option value='".$prov['id']."'>".($prov['descricao'])."</option>";
			}

			$response->setContent($buffer);
			$headers = $response->getHeaders();
		}
		return $response;
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);

		if (!$id) {
			return $this->redirect()->toRoute('projeto');
		}

		if ($id) {

			$this->getProjetoTable()->deleteProjeto($id);
    		$this->getRepresentanteProjetoTable()->deleteRepresentantesProjeto($id);

		    /* INICIO Grava log */
		    $log_acao = "projeto/delete";
		    $log_acao_id = $id;
		    $log_acao_exibicao = 'Exclui projeto';
		    $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    /* FIM Grava log */

			return $this->redirect()->toRoute('projeto');
		}

		return array(
				'id'    => $id,
				'projeto' => $this->getProjetoTable()->getProjeto($id)
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
