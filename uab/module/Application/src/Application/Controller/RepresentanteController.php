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

use Application\Model\RepresentanteTable;
use Application\Model\Representante;

use Application\Model\LogTable;
use Application\Model\Log;

class RepresentanteController extends AbstractActionController
{	          
    protected $representanteTable;	
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}			
    
	public function getRepresentanteTable()
	{
		if (!$this->representanteTable) {
			$sm = $this->getServiceLocator();
			$this->representanteTable = $sm->get('Application\Model\RepresentanteTable');
		}
		return $this->representanteTable;
	}	
	
    public function indexAction()
    {    
    /*
		$representantes = $this->getRepresentanteTable()->fetchAll();
		
		foreach ($representantes as $representante){
			$representante->nome = utf8_decode($representante->nome);
			$representante->orgao = utf8_decode($representante->orgao);
			$representante->setor_lotacao = utf8_decode($representante->setor_lotacao);
									
			$this->getRepresentanteTable()->saveRepresentante($representante);
		}
		die;
		*/
    	
    	/* INICIO Grava log */
    	$log_acao = "representante";
    	$log_acao_id = NULL;
    	$log_acao_exibicao = 'Consulta representantes TCE';
    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    	/* FIM Grava log */
    	
    	return new ViewModel(array('representantes' => $this->getRepresentanteTable()->getRepresentantesAtivos()));
    }  
	
	public function addAction()
	{
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$representante = new Representante();
			$dados_form = $request->getPost();
			 
			if ($dados_form) {				 
	
				$file = $request->getFiles()->toArray();

				$representante->nome = $dados_form['nome'];
				$representante->email = $dados_form['email'];
				$representante->cpf = $dados_form['cpf'];
				$representante->nome_cargo = $dados_form['nome_cargo'];
				$representante->funcao_confianca = $dados_form['funcao_confianca'];								
				$representante->telefone = $dados_form['telefone'];
				$representante->orgao = $dados_form['orgao'];
				$representante->setor_lotacao = $dados_form['setor_lotacao'];
				$representante->del = 0;
				
				$id = $this->getRepresentanteTable()->saveRepresentante($representante);

    			/* INICIO Grava log */
    			$log_acao = "representante/add";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Cadastra representante TCE';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
	
				return $this->redirect()->toRoute('representante');
			}
		}
	
		return new ViewModel(array(
		));
	}
	
	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		 
		if (!$id) {
			return $this->redirect()->toRoute('representante', array(
					'action' => 'index'
			));
		}
	
		try {
			$representante = $this->getRepresentanteTable()->getRepresentante($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('representante', array(
					'action' => 'index'
			));
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$dados_form = $request->getPost();
			 
			if ($dados_form) {

				$representante->nome = $dados_form['nome'];
				$representante->email = $dados_form['email'];
				$representante->telefone = $dados_form['telefone'];
				$representante->cpf = $dados_form['cpf'];
				$representante->nome_cargo = $dados_form['nome_cargo'];
				$representante->funcao_confianca = $dados_form['funcao_confianca'];												
				$representante->orgao = $dados_form['orgao'];
				$representante->setor_lotacao = $dados_form['setor_lotacao'];
				$representante->del = 0;

				$this->getRepresentanteTable()->saveRepresentante($representante);	

    			/* INICIO Grava log */
    			$log_acao = "representante/edit";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Edita representante TCE';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */
	
				return $this->redirect()->toRoute('representante');
			}
		}
	
		return array(
				'id' => $id,
				'representante' => $representante,
		);	
	}
	
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		if (!$id) {
			return $this->redirect()->toRoute('representante');
		}
	
		if ($id) {
			
			$this->getRepresentanteTable()->deleteRepresentante($id);

			/* INICIO Grava log */
			$log_acao = "representante/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui representante TCE';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */
			
			return $this->redirect()->toRoute('representante');
		}
	
		return array(
				'id'    => $id,
				'representante' => $this->getRepresentanteTable()->getRepresentante($id)
		);
	}
	
	public function detalheAction()
	{
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
