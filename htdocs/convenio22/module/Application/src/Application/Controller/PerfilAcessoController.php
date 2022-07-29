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

use Application\Model\PerfilAcesso;
use Application\Model\PerfilAcessoTable;

use Application\Model\Funcionalidade;
use Application\Model\FuncionalidadeTable;

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Zend\Session\Container;

use Application\Model\Log;
use Application\Model\LogTable;

class PerfilAcessoController extends AbstractActionController
{
	protected $perfilAcessoTable;
	protected $funcionalidadeTable;
	protected $permissaoTable;
	protected $logTable;
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}	
		
	public function indexAction()
     {
		$array_perfis = Array();
		$array_perfis_acessos = Array();
		
		$perfis = $this->getPermissaoTable()->fetchAll();
		
		foreach ($perfis as $perfil){
		
			$perfis_acessos = $this->getPerfilAcessoTable()->getPerfilAcessos($perfil->id);
			
			foreach ($perfis_acessos as $perfil_acesso){
				$array_perfis[$perfil->id][] = $perfil_acesso->funcionalidade_id; //array com os perfis e as funcionalidades que eles tem acesso
			}
		}
		
         return new ViewModel(array(
             //'perfilAcessos' => $this->getPerfilAcessoTable()->fetchAll(),
             'perfis' => $this->getPermissaoTable()->fetchAll(), //todos os perfis
             //'funcionalidades' => $this->getFuncionalidadeTable()->fetchAll(), //todas as funcionalidades
             //'array_perfis' => $array_perfis
         ));
     }
    
     public function getPermissaoTable()
     {
     	if (!$this->permissaoTable) {
     		$sm = $this->getServiceLocator();
     		$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
     	}
     	return $this->permissaoTable;
     }
     
    public function getPerfilAcessoTable()
    {
    	if (!$this->perfilAcessoTable) {
    		$sm = $this->getServiceLocator();
    		$this->perfilAcessoTable = $sm->get('Application\Model\PerfilAcessoTable');
    	}
    	return $this->perfilAcessoTable;
    }
    
    public function getFuncionalidadeTable()
    {
    	if (!$this->funcionalidadeTable) {
    		$sm = $this->getServiceLocator();
    		$this->funcionalidadeTable = $sm->get('Application\Model\FuncionalidadeTable');
    	}
    	return $this->funcionalidadeTable;
    }
    
    public function addAction()
    {    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$perfilAcesso = new PerfilAcesso();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			$this->getPerfilAcessoTable()->deletePerfilAcesso($dados_form['perfil_id']);
    			    			
    			foreach ($dados_form['funcionalidades'] as $funcionalidade_id){
    				
    				$perfilAcesso->perfil_id = $dados_form['perfil_id'];
    				$perfilAcesso->funcionalidade_id = $funcionalidade_id;    				    				
    				
    				$this->getPerfilAcessoTable()->savePerfilAcesso($perfilAcesso);
    			}    			 

    			$acao = "perfil-acesso/edit";
    			$this->salvarLog($acao, $dados_form['perfil_id']);
    			
    			return $this->redirect()->toRoute('perfil-acesso');
    		}
    	}
    	
    	return new ViewModel(array(
    			'perfilAcessos' => $this->getPerfilAcessoTable()->fetchAll(),
    	));
    }
    
    public function editAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
    	
    	if (!$id) {
    		return $this->redirect()->toRoute('perfil-acesso', array(
    				'action' => 'index'
    		));
    	}
    
    	try {
    		//$perfilAcesso = $this->getPerfilAcessoTable()->getPerfilAcessos($id);
    		$array_perfis = Array();
    		
    		$perfis = $this->getPermissaoTable()->fetchAll();
    		
    		foreach ($perfis as $perfil){
    		
    			$perfis_acessos = $this->getPerfilAcessoTable()->getPerfilAcessos($perfil->id);
    			
	    		$array_perfis[$perfil->id][] = $perfis_acessos;
	    		/*
    			echo '<pre>';
    			print_r($array_perfis);
    			*/
    			  			
    			if(count($perfis_acessos) == 0){
    				$array_perfis[$perfil->id] = Array();
    			}else{    				
	    			foreach ($perfis_acessos as $perfil_acesso){
	    				$array_perfis[$perfil->id][] = $perfil_acesso->funcionalidade_id;
	    			}
    			}
    			/*
    			echo '<pre>';
    			print_r($array_perfis);
    			die;
    			*/
    		}
    		
    		$perfil_dados = $this->getPermissaoTable()->getPermissao($id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('perfil-acesso', array(
    				'action' => 'index'
    		));
    	}
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$perfilAcesso = new PerfilAcesso();
    		$dados_form = $request->getPost();
    		 
    		if ($dados_form) {
    			    			    			 
    			$this->getPerfilAcessoTable()->deletePerfilAcesso($id);

    			foreach ($dados_form['funcionalidades'] as $funcionalidade_id){
    				    				
    				if(isset($funcionalidade_id)){
    	
	    				$perfilAcesso->permissao_id = $id;
	    				$perfilAcesso->funcionalidade_id = $funcionalidade_id;
	    	
	    				$this->getPerfilAcessoTable()->savePerfilAcesso($perfilAcesso);
    				}
    			}

    			$funcionalidades_padrao[] = 1;
    			$funcionalidades_padrao[] = 2;
    			
    			foreach ($funcionalidades_padrao as $funcionalidade_id){

    				$perfilAcesso->permissao_id = $id;
    				$perfilAcesso->funcionalidade_id = $funcionalidade_id;
    				    				
    				$this->getPerfilAcessoTable()->savePerfilAcesso($perfilAcesso);
    			}

    			/* INICIO Grava log */
    			$log_acao = "perfil-acesso/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita Perfil';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			 
    			return $this->redirect()->toRoute('perfil-acesso');
    		}
    	}


        $funcionalidade_pai = $this->getFuncionalidadeTable()->getFuncionalidadePai();
        $filhos = array();
        foreach ($funcionalidade_pai  as $v_funcionalidade_pai ){

            $funcionalidade_filho = $this->getFuncionalidadeTable()->getFuncionalidadeFilho($v_funcionalidade_pai[funcionalidade_id]);
            foreach ($funcionalidade_filho as $dados)
            {
                $filhos[$v_funcionalidade_pai['funcionalidade_id']][] = $dados;
            }

        }
    	
       	return array(
    			'perfil_dados' => $perfil_dados,
                'pai' => $this->getFuncionalidadeTable()->getFuncionalidadePai(),
                'id' => $id,
                'filhos' => $filhos,
    			//'funcionalidades' => $this->getFuncionalidadeTable()->fetchAll(), //usar quando for mostrar as funcionalidades do BD
    			'array_perfis' => $array_perfis
    	);
    }
       
    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('perfil-acesso');
    	}
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		$dados_form = $request->getPost(); 

    		if($dados_form['submit'] == "Sim"){
    			$id = (int) $request->getPost('id');
    			$this->getPerfilAcessoTable()->deletePerfilAcesso($id);
    		}
    	
    		return $this->redirect()->toRoute('perfil-acesso');
    	}
    	
    	return array(
    			'id'    => $id,
    			'perfilAcesso' => $this->getPerfilAcessoTable()->getPerfilAcesso($id)
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
