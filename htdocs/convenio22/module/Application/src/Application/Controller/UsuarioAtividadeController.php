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
use Zend\Form\Annotation\AnnotationBuilder;
use Application\Model\TestEntity;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;

use Application\Model\ProjetoTable;
use Application\Model\Projeto;


use Application\Model\UsuarioProjetoTable;
use Application\Model\UsuarioProjeto;


use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;

use Application\Model\Usuario;
use Application\Model\UsuarioTable;


use Application\Model\AtividadeProjetoTable;
use Application\Model\AtividadeProjeto;

use Application\Model\AtividadeProjetoUsuarioTable;
use Application\Model\AtividadeProjetoUsuario;

use Application\Model\LogTable;
use Application\Model\Log;

use Zend\View\Model\JsonModel;

class UsuarioAtividadeController extends AbstractActionController
{	    	    
	protected $usuario;
    protected $usuarioAtividadeTable;
    protected $projetoTable; 
    protected $atividadeProjetoTable;
    protected $atividadeProjetoUsuarioTable;
    protected $logTable;	
    protected $usuarioProjetoTable;
	
	
	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}
	
	public function getAtividadeProjetoUsuarioTable()
	{
		if (!$this->atividadeProjetoUsuarioTable) {
			$sm = $this->getServiceLocator();
			$this->atividadeProjetoUsuarioTable = $sm->get('Application\Model\AtividadeProjetoUsuarioTable');
		}
		return $this->atividadeProjetoUsuarioTable;
	}
    public function getUsuarioProjetoTable()
    {
        if (!$this->usuarioProjetoTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioProjetoTable = $sm->get('Application\Model\UsuarioProjetoTable');
        }
        return $this->usuarioProjetoTable;
    }
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

    public function consultaAction()
    {    	
    	
    	
    	$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
    		
    	if (!$projeto_id) {
    		return $this->redirect()->toRoute('projeto/meus-projetos', array(
    				'action' => 'meus-projetos'
    		));
    	}
    
    	try {
	    	$dados_sessao_atual = new Container('usuario_dados');
	    	
	    	if(!isset($dados_sessao_atual->id)){
	    		return $this->redirect()->toRoute('login', array('action'=>'index'));
	    	}
    
    		//$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario($projeto_id, $dados_sessao_atual->id);
			$usuario_atividades_meses = $this->getUsuarioAtividadeTable()->getUsuarioAtividadesAtivasByProjetoByUsuario2($projeto_id, $dados_sessao_atual->id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('projeto/meus-projetos', array(
    				'action' => 'meus-projetos'
    		));
    	}
    	
    	/* INICIO Grava log */
    	$log_acao = "usuario-atividade/consulta";
    	$log_acao_id = $projeto_id;
    	$log_acao_exibicao = 'Consulta relatório de atividades de projeto';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
    
    	return new ViewModel(array(
				'projeto_id' => $projeto_id,
    			'usuario_atividades_meses' => $usuario_atividades_meses
    	));
    }
    
	public function addAction()
	{		 
	    
		$dados_sessao_atual = new Container('usuario_dados');
		
		
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		
		 
		if (!$projeto_id) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}
		
    	$request = $this->getRequest();

        //$mes1 = date("Y")."-".date('m')."-01";
        //$mes2 = date("Y")."-".date('m')."-30";
        //$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($projeto_id, $mes1, $mes2);
        //$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);

		
    	
    	if ($request->isPost()) {
    		$usuario_atividade = new UsuarioAtividade();
	    	$dados_sessao_atual = new Container('usuario_dados');
	    	
	    	if(!isset($dados_sessao_atual->id)){
	    		return $this->redirect()->toRoute('login', array('action'=>'index'));
	    	}
	    	
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			//$usuario_atividade->projeto_id = $projeto_id;
    			$usuario_atividade->usuario_id = $dados_sessao_atual->id;
    			$usuario_atividade->texto = addslashes($dados_form['texto']);
    			$usuario_atividade->data_inicial = implode("-",array_reverse(explode("/", $dados_form['data_inicial'])));
    			$usuario_atividade->data_final = implode("-",array_reverse(explode("/", $dados_form['data_final'])));    					
    			$usuario_atividade->data_abrang_ini = implode("-",array_reverse(explode("/", $dados_form['data_abrang_ini'])));
    			$usuario_atividade->data_abrang_fim = implode("-",array_reverse(explode("/", $dados_form['data_abrang_fim'])));    					
    			$usuario_atividade->data_lanc = date('Y-m-d');
				date_default_timezone_set('America/Cuiaba');
    			$usuario_atividade->hora_lanc = date('H:i:s');    					
    			$usuario_atividade->data_relatorio = implode("-",array_reverse(explode("/", $dados_form['data_relatorio'])));    	    			
				$usuario_atividade->ip = getenv("REMOTE_ADDR");
    			$usuario_atividade->del = 0;
    			    			    			
    			$usuario_atividade_id = $this->getUsuarioAtividadeTable()->saveUsuarioAtividade($usuario_atividade);

    			/* INICIO Grava log */
    			$log_acao = "usuario-atividade/add";
    			$log_acao_id = $usuario_atividade_id;
    			$log_acao_exibicao = 'Cadastra atividade mensal de colaborador';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
				return $this->redirect()->toRoute('projeto/meus-projetos', array('action'=>'meus-projetos'));
    		}
    	}

        $atividades_bd =  $this->getAtividadeProjetoTable()->fetchAll();
        $atividades = array();
        foreach ($atividades_bd as $atividade){
            $atividades[$atividade->id] = $atividade->descricao;
        }


        //$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
		$usuario_projetos = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);    	    	
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);



		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);
        $atividade_usuario_projeto = array();

        foreach ($usuario_projetos_bd as $usuario_projeto){
            $atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $dados_sessao_atual->id) ;
        }


//        echo '<pre>';
//        print_r($atividade_usuario_projeto);
//        die;
    	return new ViewModel(array(
				'atividades_projeto_matriz' => $this->getAtividadeProjetoTable()->getAtividadesMatriz($projeto_id),				
				'usuario' => $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id),
    			'projeto_id' => $projeto_id,
				'usuario_projetos' => $usuario_projetos,
                'atividade_usuario_projeto' => $atividade_usuario_projeto,
				'projeto' => $projeto,
    			'atividades_projeto' => $atividades_projeto
    	));
	}	
	
	
	public function addEspecificoAction()
	{		 
	    
		$dados_sessao_atual = new Container('usuario_dados');
		
		
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
		
		 
		if (!$projeto_id) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}
		
    	$request = $this->getRequest();

        //$mes1 = date("Y")."-".date('m')."-01";
        //$mes2 = date("Y")."-".date('m')."-30";
        //$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($projeto_id, $mes1, $mes2);
        //$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);

		
    	
    	if ($request->isPost()) {
    		$usuario_atividade = new UsuarioAtividade();
	    	$dados_sessao_atual = new Container('usuario_dados');
	    	
	    	if(!isset($dados_sessao_atual->id)){
	    		return $this->redirect()->toRoute('login', array('action'=>'index'));
	    	}
	    	
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			//$usuario_atividade->projeto_id = $projeto_id;
    			$usuario_atividade->projeto_id = $projeto_id;
				$usuario_atividade->usuario_id = $dados_sessao_atual->id;
    			$usuario_atividade->texto = addslashes($dados_form['texto']);
    			$usuario_atividade->data_inicial = implode("-",array_reverse(explode("/", $dados_form['data_inicial'])));
    			$usuario_atividade->data_final = implode("-",array_reverse(explode("/", $dados_form['data_final'])));    					
    			$usuario_atividade->data_abrang_ini = implode("-",array_reverse(explode("/", $dados_form['data_abrang_ini'])));
    			$usuario_atividade->data_abrang_fim = implode("-",array_reverse(explode("/", $dados_form['data_abrang_fim'])));    					
    			$usuario_atividade->data_lanc = date('Y-m-d');
				date_default_timezone_set('America/Cuiaba');
    			$usuario_atividade->hora_lanc = date('H:i:s');    					
    			$usuario_atividade->data_relatorio = implode("-",array_reverse(explode("/", $dados_form['data_relatorio'])));    	    			
				$usuario_atividade->ip = getenv("REMOTE_ADDR");
    			$usuario_atividade->del = 0;
    			    			    			
    			$usuario_atividade_id = $this->getUsuarioAtividadeTable()->saveUsuarioAtividade($usuario_atividade);

    			/* INICIO Grava log */
    			$log_acao = "usuario-atividade/add";
    			$log_acao_id = $usuario_atividade_id;
    			$log_acao_exibicao = 'Cadastra atividade mensal de colaborador';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
    			
				return $this->redirect()->toRoute('projeto/meus-projetos', array('action'=>'meus-projetos'));
    		}
    	}

        $atividades_bd =  $this->getAtividadeProjetoTable()->fetchAll();
        $atividades = array();
        foreach ($atividades_bd as $atividade){
            $atividades[$atividade->id] = $atividade->descricao;
        }


        //$projeto = $this->getProjetoTable()->getProjeto($projeto_id);
		$usuario_projetos = $this->getUsuarioProjetoTable()->getUsuarioProjetosEspecificosAtivos1($dados_sessao_atual->id, $projeto_id);    	    	
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);



		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);
        $atividade_usuario_projeto = array();

        foreach ($usuario_projetos_bd as $usuario_projeto){
            $atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $dados_sessao_atual->id) ;
        }


//        echo '<pre>';
//        print_r($atividade_usuario_projeto);
//        die;
    	return new ViewModel(array(
				'atividades_projeto_matriz' => $this->getAtividadeProjetoTable()->getAtividadesMatriz($projeto_id),				
				'usuario' => $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id),
    			'projeto_id' => $projeto_id,
				'usuario_projetos' => $usuario_projetos,
                'atividade_usuario_projeto' => $atividade_usuario_projeto,
				'projeto' => $projeto,
    			'atividades_projeto' => $atividades_projeto
    	));
	}	
	
	public function editAction()
	{
		
		$dados_sessao_atual = new Container('usuario_dados');
		$id = (int) $this->params()->fromRoute('id', 0);
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
			
		if (!$id) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}
	
		$request = $this->getRequest();
	
		//try {
			$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
    		//$projeto = $this->getProjetoTable()->getProjeto($usuario_atividade->projeto_id);
    		
    		$mes1 = date("Y")."-".date('m')."-01";
    		$mes2 = date("Y")."-".date('m')."-30";
    		    		
    		//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_atividade->projeto_id); //como pgar mes1 e mes2
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}*/
		 
		if ($request->isPost()) {
			$dados_sessao_atual = new Container('usuario_dados');
	
			if(!isset($dados_sessao_atual->id)){
				return $this->redirect()->toRoute('login', array('action'=>'index'));
			}
	
			$dados_form = $request->getPost();
			 
			if ($dados_form) {
				 
				//$usuario_atividade->projeto_id = $projeto_id;
				$usuario_atividade->usuario_id = $dados_sessao_atual->id;
				$usuario_atividade->texto = nl2br($dados_form['texto']);
				$usuario_atividade->data_inicial = implode("-",array_reverse(explode("/", $dados_form['data_inicial'])));
				$usuario_atividade->data_final = implode("-",array_reverse(explode("/", $dados_form['data_final'])));
				$usuario_atividade->data_abrang_ini = implode("-",array_reverse(explode("/", $dados_form['data_abrang_ini'])));
				$usuario_atividade->data_abrang_fim = implode("-",array_reverse(explode("/", $dados_form['data_abrang_fim'])));
				$usuario_atividade->data_lanc = date('Y-m-d');
				date_default_timezone_set('America/Cuiaba');
				$usuario_atividade->hora_lanc = date('H:i:s');
				$usuario_atividade->data_relatorio = implode("-",array_reverse(explode("/", $dados_form['data_relatorio'])));
				$usuario_atividade->ip = getenv("REMOTE_ADDR");
				$usuario_atividade->del = 0;
				 
				$this->getUsuarioAtividadeTable()->saveUsuarioAtividade($usuario_atividade);
				/*
				 $acao = "atividade_projeto/add";
				 $this->salvarLog($acao, $id_ind);
					*/
				return $this->redirect()->toRoute('projeto/meus-projetos');
			}
		}
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);
		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);    	    	
		
		
		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetosAtivos($dados_sessao_atual->id);
        $atividade_usuario_projeto = array();

        foreach ($usuario_projetos_bd1 as $usuario_projeto){
            $atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $dados_sessao_atual->id) ;
        }
		return new ViewModel(array(
				'usuario' => $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id),
				'atividades_projeto_matriz' => $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_atividade->projeto_id),
				'id' => $id,
				'usuario_atividade' => $usuario_atividade,
				'atividade_usuario_projeto' => $atividade_usuario_projeto,
				'projeto' => $projeto,
				'atividades_projeto' => $atividades_projeto,
				'usuario_projetos_bd' => $usuario_projetos_bd,
		));
	}
	
	
	public function editEspecificoAction()
	{
		
		$dados_sessao_atual = new Container('usuario_dados');
		$id = (int) $this->params()->fromRoute('id', 0);
		$projeto_id = (int) $this->params()->fromRoute('projeto_id', 0);
			
		if (!$id) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}
	
		$request = $this->getRequest();
	
		//try {
			$usuario_atividade = $this->getUsuarioAtividadeTable()->getUsuarioAtividade($id);
    		//$projeto = $this->getProjetoTable()->getProjeto($usuario_atividade->projeto_id);
    		
    		$mes1 = date("Y")."-".date('m')."-01";
    		$mes2 = date("Y")."-".date('m')."-30";
    		    		
    		//$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_atividade->projeto_id); //como pgar mes1 e mes2
		/*}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('projeto/meus-projetos', array(
					'action' => 'meus-projetos'
			));
		}*/
		 
		if ($request->isPost()) {
			$dados_sessao_atual = new Container('usuario_dados');
	
			if(!isset($dados_sessao_atual->id)){
				return $this->redirect()->toRoute('login', array('action'=>'index'));
			}
	
			$dados_form = $request->getPost();
			 
			if ($dados_form) {
				 
				//$usuario_atividade->projeto_id = $projeto_id;
				$usuario_atividade->usuario_id = $dados_sessao_atual->id;
				$usuario_atividade->texto = nl2br($dados_form['texto']);
				$usuario_atividade->data_inicial = implode("-",array_reverse(explode("/", $dados_form['data_inicial'])));
				$usuario_atividade->data_final = implode("-",array_reverse(explode("/", $dados_form['data_final'])));
				$usuario_atividade->data_abrang_ini = implode("-",array_reverse(explode("/", $dados_form['data_abrang_ini'])));
				$usuario_atividade->data_abrang_fim = implode("-",array_reverse(explode("/", $dados_form['data_abrang_fim'])));
				$usuario_atividade->data_lanc = date('Y-m-d');
				date_default_timezone_set('America/Cuiaba');
				$usuario_atividade->hora_lanc = date('H:i:s');
				$usuario_atividade->data_relatorio = implode("-",array_reverse(explode("/", $dados_form['data_relatorio'])));
				$usuario_atividade->ip = getenv("REMOTE_ADDR");
				$usuario_atividade->del = 0;
				 
				$this->getUsuarioAtividadeTable()->saveUsuarioAtividade($usuario_atividade);
				/*
				 $acao = "atividade_projeto/add";
				 $this->salvarLog($acao, $id_ind);
					*/
				return $this->redirect()->toRoute('projeto/meus-projetos');
			}
		}
		$atividades_projeto = $this->getAtividadeProjetoTable()->getAtividadesAtivasPorUsuario($dados_sessao_atual->id);
		$usuario_projetos_bd = $this->getUsuarioProjetoTable()->getUsuarioProjetoEspecificoAtivos($dados_sessao_atual->id,$projeto_id);    	    	
		
		
		$usuario_projetos_bd1 = $this->getUsuarioProjetoTable()->getUsuarioProjetoEspecificoAtivos($dados_sessao_atual->id, $projeto_id);
        $atividade_usuario_projeto = array();

        foreach ($usuario_projetos_bd1 as $usuario_projeto){
            $atividade_usuario_projeto[$usuario_projeto['projeto_id']] = $this->getUsuarioAtividadeTable()->getAtividadesProjetoUsuario($usuario_projeto['projeto_id'], $dados_sessao_atual->id) ;
        }
		return new ViewModel(array(
				'usuario' => $this->getUsuarioTable()->getUsuario($dados_sessao_atual->id),
				'atividades_projeto_matriz' => $this->getAtividadeProjetoTable()->getAtividadesMatriz($usuario_atividade->projeto_id),
				'id' => $id,
				'usuario_atividade' => $usuario_atividade,
				'atividade_usuario_projeto' => $atividade_usuario_projeto,
				'projeto' => $projeto,
				'atividades_projeto' => $atividades_projeto,
				'usuario_projetos_bd' => $usuario_projetos_bd,
		));
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
