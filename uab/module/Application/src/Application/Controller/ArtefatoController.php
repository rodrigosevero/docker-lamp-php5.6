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
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use Application\Model\Artefato;
use Application\Model\ArtefatoTable;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use Application\Model\LogTable;
use Application\Model\Log;

class ArtefatoController extends AbstractActionController
{	    
    protected $artefatoTable;
    protected $logTable;	
    
	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}			
    
	public function getArtefatoTable()
	{
		if (!$this->artefatoTable) {
			$sm = $this->getServiceLocator();
			$this->artefatoTable = $sm->get('Application\Model\ArtefatoTable');
		}
		return $this->artefatoTable;
	}	
	
    public function indexAction()
    {    	
    }	
    
    public function addAction()
    {

    	$atividade_id = (int) $this->params()->fromRoute('atividade_id', 0);

    	if (!$atividade_id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
		/*
    	try {
    		$artefatos = $this->getArtefatoTable()->getArtefatosAtivosByArea($atividade_id);
    	}
    	catch (\Exception $ex) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
    	*/
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$artefato = new Artefato();
    		$dados_form = $request->getPost();
    		 
    		if ($dados_form) {
    			
    			if ($_FILES['arquivo']['error'] == 0) {

    				$file = $request->getFiles()->toArray();
    				
    				$ext = explode('.', $file['arquivo']['name']);
    				$ext = $ext[1];    				    				
    				
    				
    				$artefato->atividade_id = $atividade_id;
    				$artefato->tipo = $dados_form['tipo'];
    				$artefato->descricao = $dados_form['descricao'];
    				$artefato->legenda = $dados_form['legenda'];
    				$artefato->data = implode("-",array_reverse(explode("/", $dados_form['data'])));
    				$artefato->local = $dados_form['local'];
    				$artefato->del = 0;
    				$artefato->arquivo = md5(time()).'.'.$ext;

    				$id = $this->getArtefatoTable()->saveArtefato($artefato);
    				
	    			/* INICIO Grava log */
	    			$log_acao = "artefato/add";
	    			$log_acao_id = $id;
	    			$log_acao_exibicao = 'Cadastra artefato de cumprimento de objeto';
	    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
	    			/* FIM Grava log */
    				
    				/* Diretorio Local */
    				$diretorio = __DIR__;
    				$diretorio = str_replace("\\", '/', $diretorio);
    				$diretorio = explode("convenio", $diretorio);
    				/* Diretorio Local */
    				
    				$target_file = $diretorio[0].'convenio/public/artefatos/'.$artefato->arquivo;
    				    				    				    				
    				move_uploaded_file($_FILES["arquivo"]["tmp_name"], $target_file);    
    			}    			

    			return $this->redirect()->toRoute('cumprimento-objeto/add', array(
    					'action' => 'index', 'atividade_id' => $atividade_id
    			));
    		}
    	}
    	 
    	return new ViewModel(array(
    			'artefatos' => $this->getArtefatoTable()->fetchAll(),
    			'qt_artefatos_ativos' => count($this->getArtefatoTable()->getArtefatosAtivos())
    	));
    }
           
    public function deleteAction()
    {
    	$atividade_id = (int) $this->params()->fromRoute('atividade_id', 0);
    	$id = (int) $this->params()->fromRoute('id', 0);

    	if (!$id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}
    	
    	if (!$atividade_id) {
    		return $this->redirect()->toRoute('atividade-ptc', array(
    				'action' => 'index'
    		));
    	}

    	if ($id) {

    			$artefato = $this->getArtefatoTable()->getArtefato($id);
    			$this->getArtefatoTable()->deleteArtefato($id);
    			
    			/* INICIO Grava log */
    			$log_acao = "artefato/delete";
    			$log_acao_id = $id;
    			$log_acao_exibicao = 'Exclui artefato de cumprimento de objeto';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */

    			/* Diretorio Local */
    			$dir = __DIR__;
    			$dir = str_replace("\\", '/', $dir);
    			$dir = explode("convenio", $dir);
    			/* Diretorio Local */
    			
    			$target_file = $dir[0].'convenio/public/artefatos/'.$artefato->arquivo;    			    			

    			unlink($target_file);    	

    			return $this->redirect()->toRoute('cumprimento-objeto/add', array(
    					'action' => 'index', 'atividade_id' => $atividade_id
    			));
    	}
    	 
    	return array(
    			'id'    => $id,
    			'artefato' => $this->getArtefatoTable()->getArtefato($id)
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
















