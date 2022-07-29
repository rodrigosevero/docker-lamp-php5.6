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

use Application\Model\AtividadeProjetoUsuarioTable;
use Application\Model\AtividadeProjetoUsuario;

use Application\Model\LogTable;
use Application\Model\Log;

class AtividadeProjetoUsuarioController extends AbstractActionController
{	    	    
    protected $atividadeProjetoUsuarioTable;
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}		
    
	public function getAtividadeProjetoUsuarioTable()
	{
		if (!$this->atividadeProjetoUsuarioTable) {
			$sm = $this->getServiceLocator();
			$this->atividadeProjetoUsuarioTable = $sm->get('Application\Model\atividadeProjetoUsuarioTable');
		}
		return $this->atividadeProjetoUsuarioTable;
	}	
    
    public function indexAction()
    {
    	
    	return new ViewModel();
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
