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

use Application\Model\LogTable;
use Application\Model\Log;

class UsuarioProjetoController extends AbstractActionController
{	       
    protected $usuarioTable;    
    protected $usuarioProjetoTable;
    protected $areaTable;    
    protected $representanteTable;
    protected $projetoTable;
    protected $logTable;	
    
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
        
	public function getRepresentanteTable()
	{
		if (!$this->representanteTable) {
			$sm = $this->getServiceLocator();
			$this->representanteTable = $sm->get('Application\Model\RepresentanteTable');
		}
		return $this->representanteTable;
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
	
    public function consultaAction()
    {    		
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);
		 
		if (!$usuario_id) {
			return $this->redirect()->toRoute('colaborador', array(
					'action' => 'index'
			));
		}
		
		try {
			$colaborador = $this->getUsuarioTable()->getUsuario($usuario_id);
		} catch (Exception $e) {
			return $this->redirect()->toRoute('colaborador', array(
					'action' => 'index'
			));
		}
    	
    	$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuariosProjetosByUsuario($usuario_id);    	
    	
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
    	
    	/* INICIO Grava log */
    	$log_acao = "usuario-projeto";
    	$log_acao_id = $usuario_id;
    	$log_acao_exibicao = 'Consulta projetos de colaborador';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
		
		return new ViewModel(array(
				'usuario_id' => $usuario_id,
				'colaborador' => $colaborador,
    			'areas' => $areas,
    			'projetos' => $projetos,
    			'usuario_projetos' => $usuario_projetos_bd,
    			'representantes' => $representantes,
		));    	
    }  
	
	public function addAction()
	{
		$usuario_id = (int) $this->params()->fromRoute('usuario_id', 0);
		 
		if (!$usuario_id) {
			return $this->redirect()->toRoute('colaborador', array(
					'action' => 'index'
			));
		}
		
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$dados_form = $request->getPost();
			 
			if ($dados_form) {		
				
				foreach($dados_form['projeto_id'] as $projeto_id) {
					$usuario_projeto = new UsuarioProjeto();
					$usuario_projeto->projeto_id = $projeto_id;
					$usuario_projeto->usuario_id = $usuario_id;
					$usuario_projeto->representante_id = 0;
					$usuario_projeto->del = 0;
					
					$usuario_projeto_id = $this->getUsuarioProjetoTable()->saveUsuarioProjeto($usuario_projeto);

	    			/* INICIO Grava log */
	    			$log_acao = "usuario-projeto/add";
	    			$log_acao_id = $usuario_projeto_id;
	    			$log_acao_exibicao = 'Cadastra projeto de colaborador';
	    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
	    			/* FIM Grava log */
				}

				return $this->redirect()->toRoute('usuario-projeto/consulta', array(
						'action' => 'consulta', 'usuario_id' => $usuario_id
				));
			}
		}
		
		return new ViewModel(array());
	}
	
	public function setRepresentanteAction()
	{		
		//$request = $this->getRequest();
		$response = $this->getResponse();
		
		$representante_id = (int) $this->params()->fromRoute('id', 0);
		$usuario_projeto_id = (int) $this->params()->fromRoute('usuario_projeto_id', 0);
		
		//if ($request->isPost()) {
			$response->setStatusCode(200);
			//$dados_form = $request->getPost();
		
			
			
			//if ($dados_form) {		
				//$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($dados_form['usuario_projeto_id']);
				//$usuario_projeto->representante_id = $dados_form['representante_id'];
				
				$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($usuario_projeto_id);
				//echo '<pre>';
				//print_r($usuario_projeto);
				
				
				$usuario_projeto->representante_id = $representante_id;
				$usuario_projeto->del = 0;
	
				$this->getUsuarioProjetoTable()->saveUsuarioProjeto($usuario_projeto);
				
    			/* INICIO Grava log */
    			$log_acao = "usuario-projeto/set-representante";
    			$log_acao_id = $dados_form['usuario_projeto_id'];
    			$log_acao_exibicao = 'Cadastra representante de projeto';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */

				$buffer = $usuario_projeto->usuario_id;
				$response->setContent($buffer);
				$headers = $response->getHeaders();
			//}
		//}

		return $response;		
	}
		
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if (!$id) {
			return $this->redirect()->toRoute('colaborador', array(
					'action' => 'index'
			));
		}
		
		try {

			$usuario_projeto = $this->getUsuarioProjetoTable()->getUsuarioProjeto($id);
				
			$this->getUsuarioProjetoTable()->deleteUsuarioProjeto($id);

    		/* INICIO Grava log */
    		$log_acao = "usuario-projeto/delete";
    		$log_acao_id = $id;
    		$log_acao_exibicao = 'Exclui projeto de colaborador';
    		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    		/* FIM Grava log */
			
			return $this->redirect()->toRoute('usuario-projeto/consulta', array(
					'action' => 'consulta', 'usuario_id' => $usuario_projeto->usuario_id
			));
			
		} catch (Exception $e){
			return $this->redirect()->toRoute('usuario-projeto/consulta', array(
					'action' => 'consulta', 'usuario_id' => $usuario_projeto->usuario_id
			));
		}
	
		return array(
				'id'    => $id,
				'usuario_projeto' => $this->getUsuarioTable()->getUsuario($id)
		);
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
