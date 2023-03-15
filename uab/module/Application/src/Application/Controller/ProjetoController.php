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

use Application\Model\UsuarioProjeto;
use Application\Model\UsuarioProjetoTable;

use Application\Model\RepresentanteProjeto;
use Application\Model\RepresentanteProjetoTable;
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
    protected $usuarioProjetoTable;
    protected $logTable;	
    
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
	
    public function indexAction()
    {    	
    	
    	
    	$projetos1 = $this->getProjetoTable()->getProjetosAtivos();
    	$projetos_bd = $this->getProjetoTable()->getProjetosAtivosDiferenteMatriz();
    	$projetos_matriz_bd = $this->getProjetoTable()->getProjetosByTipoProjeto(9);
    	
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
    	$projetos_filhos = array();
    	foreach ($projetos_bd as $projeto){
    		//echo '<pre>';
    		//print_r($projeto);
    		$atividades_projetos[$projeto['id']] = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto['id']);
    		$usuarios_projetos[$projeto['id']] = $this->getUsuarioProjetoTable()->getUsuariosProjetosAtivosByProjeto($projeto['id']);
			$projetos_filhos[$projeto['id']] = $this->getProjetoTable()->getProjetosAtivosFilho($projeto['id']);
    	}						
		
    	
    	foreach ($projetos_matriz_bd as $projeto){
    		//echo '<pre>';
    		//print_r($projeto);
    		$atividades_projetos[$projeto['id']] = $this->getAtividadeProjetoTable()->getAtividadesAtivasByProjeto($projeto['id']);
    		$usuarios_projetos[$projeto['id']] = count($this->getUsuarioProjetoTable()->getUsuariosProjetosAtivosByProjeto($projeto['id']));			
    	}
    	
    	$projetos_matriz = $this->getProjetoTable()->getProjetosByTipoProjeto(9);    		
    	$projetos = $this->getProjetoTable()->getProjetosAtivosDiferenteMatriz();

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
    		'usuarios' => $usuarios,
    		'representantes' => $representantes,
    		'projetos_representantes' => $representante_projetos,
			'projetos_filhos_geral' => $projetos_filhos_geral,
			'projetos_filhos' => $projetos_filhos,
    		'projetos_matriz_responsabilidade' => $projetos_matriz,
    		'atividades_projetos' => $atividades_projetos,
    		'usuarios_projetos' => $usuarios_projetos
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
		'projeto' => $projeto
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
    	
    	try {
    		$usuario = $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id);
    			
    		$projetos_bd = $this->getProjetoTable()->fetchAll();
    		$projetos = array();
    		 
    		foreach ($projetos_bd as $projeto){
    			$projetos[$projeto->id] = ($projeto->descricao);
    		}
    	 
	    	$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);
	    	$usuarios_projetos = array();
	    	 
	    	foreach ($usuario_projetos_bd as $usuario_projeto){
	    		$usuarios_projetos[$usuario_projeto['id']] = $usuario_projeto['projeto_id'];
	    	}
    	}
    	catch (\Exception $ex) {
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
    			'usuario' => $usuario,
    			'projetos' => $projetos,
    			'usuarios_projetos' => $usuarios_projetos
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
    			
				if(isset($dados_form['pai']) && $dados_form['pai'] != NULL ){ $projeto->pai = $dados_form['pai'];} else {$projeto->pai = 0;}
    			$projeto->coordenador_tce_mpc = $dados_form['coordenador_tce_mpc'];
				$projeto->data_inicio = date('Y-m-d', strtotime($dados_form['data_inicio']));
				$projeto->data_fim = date('Y-m-d', strtotime($dados_form['data_fim']));
				if(isset($dados_form['carga_horaria']) && $dados_form['carga_horaria'] != NULL ){$projeto->carga_horaria = $dados_form['carga_horaria'];}
				if(isset($dados_form['vagas_ofertadas']) && $dados_form['vagas_ofertadas'] != NULL ){$projeto->vagas_ofertadas = $dados_form['vagas_ofertadas'];}
				if(isset($dados_form['entidade_certificadora ']) && $dados_form['entidade_certificadora '] != NULL ){$projeto->entidade_certificadora = $dados_form['entidade_certificadora '];}
				if(isset($dados_form['status']) && $dados_form['status'] != NULL ){$projeto->status = $dados_form['status'];}
				
				$projeto->descricao = $dados_form['descricao'];
				//$projeto->codigo_projeto = $dados_form['codigo_projeto'];
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
                $projeto->status = $dados_form['status'];
                $projeto->codigo_projeto = $dados_form['codigo_projeto'];
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
                $projeto->codigo_projeto = $dados_form['codigo_projeto'];
    			$projeto->coordenador_tce_mpc = $dados_form['coordenador_tce_mpc'];
				$projeto->data_inicio = date('Y-m-d', strtotime($dados_form['data_inicio']));
				$projeto->data_fim = date('Y-m-d', strtotime($dados_form['data_fim']));
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
