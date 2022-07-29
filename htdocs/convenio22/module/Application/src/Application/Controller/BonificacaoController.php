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

use Application\Model\NucleoTable;
use Application\Model\Nucleo;

use Application\Model\AreaTable;
use Application\Model\Area;

use Application\Model\UsuarioTable;
use Application\Model\Usuario;

use Application\Model\LogTable;
use Application\Model\Log;

use Application\Model\BonificacaoTable;
use Application\Model\Bonificacao;


class BonificacaoController extends AbstractActionController
{
	protected $nucleoTable;
	protected $areaTable;
	protected $usuarioTable;
	protected $logTable;
	protected $funcaoTable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getBonificacaoTable()
	{
		if (!$this->funcaoTable) {
			$sm = $this->getServiceLocator();
			$this->bonificacaoTable = $sm->get('Application\Model\BonificacaoTable');
		}
		return $this->bonificacaoTable;
	}

	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}

	public function getAreaTable()
	{
		if (!$this->areaTable) {
			$sm = $this->getServiceLocator();
			$this->areaTable = $sm->get('Application\Model\AreaTable');
		}
		return $this->areaTable;
	}


	public function getNucleoTable()
	{
		if (!$this->nucleoTable) {
			$sm = $this->getServiceLocator();
			$this->nucleoTable = $sm->get('Application\Model\NucleoTable');
		}
		return $this->nucleoTable;
	}

	public function indexAction()
	{
        
		$bonificacoes = $this->getBonificacaoTable()->getBonificacoes();

		/* INICIO Grava log */
		$log_acao = "funcoes";
		$log_acao_id = 0;
		$log_acao_exibicao = 'Lista funcões';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'bonificacoes' => $bonificacoes
		));
	}


	public function addAction()
	{

		$request = $this->getRequest();

		if ($request->isPost()) {
			$bonificacao = new Bonificacao();
			$dados_form = $request->getPost();

			if ($dados_form) {

				$bonificacao->descricao = $dados_form['descricao'];
				$bonificacao->carga_horaria = $dados_form['carga_horaria'];
				$bonificacao->meses = $dados_form['meses'];
				$bonificacao->codigo = $dados_form['codigo'];

				$valor_inicial = str_replace(['.'],'', $dados_form['valor_inicial']);
    			$valor_inicial = str_replace([','],'.', $valor_inicial);								
				$bonificacao->valor_inicial = (empty($dados_form['valor_inicial'])) ? NULL : $valor_inicial;

				$valor_medio = str_replace(['.'],'', $dados_form['valor_medio']);
    			$valor_medio = str_replace([','],'.', $valor_medio);				
				$bonificacao->valor_medio = (empty($dados_form['valor_medio'])) ? NULL : $valor_medio;
				

				$valor_final = str_replace(['.'],'', $dados_form['valor_final']);
    			$valor_final = str_replace([','],'.', $valor_final);				
				$bonificacao->valor_final = (empty($dados_form['valor_final'])) ? NULL : $valor_final;

				$bonificacao->quantidade = $dados_form['quantidade'];
				$bonificacao->requisitos = $dados_form['requisitos'];


				$binificacao_id = $this->getBonificacaoTable()->saveBonificacao($bonificacao);

				/* INICIO Grava log */
				$log_acao = "bonificacao/add";
				$log_acao_id = $bonificacao_id;
				$log_acao_exibicao = 'Cadastra bonificação';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('bonificacao');
			}
		}



		// $coordenadores_nucleo = array();
		// $usuarios_coordenadores = $this->getUsuarioTable()->getUsuariosByPermissao('4'); //o id do perfil 'coordenadores de nucleo' é 4
		// foreach ($usuarios_coordenadores as $usuario) {
		// 	$coordenadores_nucleo[$usuario->id] = $usuario->nome;
		// }

		return new ViewModel(array());
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$bonificacao = $this->getBonificacaoTable()->getBonificacao($id);

		$request = $this->getRequest();

		if ($request->isPost()) {
            
			$bonificacao = new Bonificacao();
			$dados_form = $request->getPost();
            
			if ($dados_form) {

				$bonificacao->id = $id;
				$bonificacao->descricao = $dados_form['descricao'];
				$bonificacao->carga_horaria = $dados_form['carga_horaria'];
				$bonificacao->meses = $dados_form['meses'];
				
				$valor_inicial = str_replace(['.'],'', $dados_form['valor_inicial']);
    			$valor_inicial = str_replace([','],'.', $valor_inicial);								
				$bonificacao->valor_inicial = (empty($dados_form['valor_inicial'])) ? NULL : $valor_inicial;

				$valor_medio = str_replace(['.'],'', $dados_form['valor_medio']);
    			$valor_medio = str_replace([','],'.', $valor_medio);				
				$bonificacao->valor_medio = (empty($dados_form['valor_medio'])) ? NULL : $valor_medio;
				

				$valor_final = str_replace(['.'],'', $dados_form['valor_final']);
    			$valor_final = str_replace([','],'.', $valor_final);				
				$bonificacao->valor_final = (empty($dados_form['valor_final'])) ? NULL : $valor_final;
				
				$bonificacao->quantidade = $dados_form['quantidade'];
				$bonificacao->requisitos = $dados_form['requisitos'];

          
				$funcao_id = $this->getBonificacaoTable()->saveBonificacao($bonificacao);
            
				/* INICIO Grava log */
				$log_acao = "bonificacao/edit";
				$log_acao_id = $funcao_id;
				$log_acao_exibicao = 'Edita bonificacao';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('bonificacao');
			}
			
		}


		return new ViewModel(array(
			'id' => $id,
			'bonificacao' => $bonificacao,
		));
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);				

		if ($id) {

			$this->getBonificacaoTable()->deleteBonificacao($id);

			/* INICIO Grava log */
			$log_acao = "bonificacao/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui bonificação';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('bonificacao', array(
				'action' => 'index'
			));
		}

		return array(
		
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
