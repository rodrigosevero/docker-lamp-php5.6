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

use Application\Model\CumprimentoObjetoAtividadeTable;
use Application\Model\CumprimentoObjetoAtividade;

use Application\Model\LogTable;
use Application\Model\Log;

class CumprimentoObjetoAtividadeController extends AbstractActionController
{	    	    
    protected $cumprimentoObjetoAtividadeTable;
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}			
        
	public function getCumprimentoObjetoAtividadeTable()
	{
		if (!$this->cumprimentoObjetoAtividadeTable) {
			$sm = $this->getServiceLocator();
			$this->cumprimentoObjetoAtividadeTable = $sm->get('Application\Model\CumprimentoObjetoAtividadeTable');
		}
		return $this->cumprimentoObjetoAtividadeTable;
	}	
		
	public function addAction()
	{		 
    	$atividade_id = (int) $this->params()->fromRoute('atividade_id', 0);
    	$request = $this->getRequest();

    	if (!$atividade_id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
    	    	
    	if ($request->isPost()) {
    		$cumprimento_objeto_atividade = new CumprimentoObjetoAtividade();
    		$dados_form = $request->getPost();
    	
    		if ($dados_form) {
    			
    			if(is_array($dados_form['atividade_epe_id']) && $dados_form['atividade_epe_id'][0] != ""){
    				
    				foreach ($dados_form['atividade_epe_id'] as $atividade_epe_id){
    					
		    			$cumprimento_objeto_atividade->area_id = $dados_form['projeto_id'];
		    			$cumprimento_objeto_atividade->atividade_epe_id = $atividade_epe_id;
		    			$cumprimento_objeto_atividade->atividade_ptc_id = $atividade_id;
		    			$cumprimento_objeto_atividade->del = 0;
		    					    			
		    			$cumprimento_objeto_atividade_id = $this->getCumprimentoObjetoAtividadeTable()->saveCumprimentoObjetoAtividade($cumprimento_objeto_atividade);

		    			/* INICIO Grava log */
		    			$log_acao = "cumprimento-objeto-atividade/add";
		    			$log_acao_id = $cumprimento_objeto_atividade_id;
		    			$log_acao_exibicao = 'Cadastra atividade de cumprimento de objeto';
		    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    			/* FIM Grava log */
    				}
    			}
    			
    			return $this->redirect()->toRoute('cumprimento-objeto/add', array(
    					'action' => 'index', 'atividade_id' => $atividade_id
    			));
    		}
    	}
    	
    	return new ViewModel(array());
	}	
	
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
	
		if (!$id) {
			return $this->redirect()->toRoute('atividade-ptc', array(
					'action' => 'index'
			));
		}
		
		if ($id) {
	
			$cumprimento_objeto_atividade = $this->getCumprimentoObjetoAtividadeTable()->getCumprimentoObjetoAtividade($id);
			$this->getCumprimentoObjetoAtividadeTable()->deleteCumprimentoObjetoAtividade($id);
    			
    		/* INICIO Grava log */
    		$log_acao = "cumprimento-objeto-atividade/delete";
    		$log_acao_id = $id;
    		$log_acao_exibicao = 'Exclui atividade de cumprimento de objeto';
    		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    		/* FIM Grava log */
	
			return $this->redirect()->toRoute('cumprimento-objeto/add', array(
					'action' => 'index', 'atividade_id' => $cumprimento_objeto_atividade->atividade_ptc_id
			));
		}
	
		return array(
				'id'    => $id,
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
