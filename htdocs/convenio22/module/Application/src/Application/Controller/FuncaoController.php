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

use Application\Model\FuncaoTable;
use Application\Model\Funcao;


class FuncaoController extends AbstractActionController
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

	public function getFuncaoTable()
	{
		if (!$this->funcaoTable) {
			$sm = $this->getServiceLocator();
			$this->funcaoTable = $sm->get('Application\Model\FuncaoTable');
		}
		return $this->funcaoTable;
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

		$funcoes = $this->getFuncaoTable()->getFuncoes();

		/* INICIO Grava log */
		$log_acao = "funcoes";
		$log_acao_id = 0;
		$log_acao_exibicao = 'Lista funcões';
		$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		/* FIM Grava log */

		return new ViewModel(array(
			'funcoes' => $funcoes
		));
	}


	public function addAction()
	{

		$request = $this->getRequest();

		if ($request->isPost()) {
			$funcao = new Funcao();
			$dados_form = $request->getPost();

			if ($dados_form) {


			

				$funcao->descricao = $dados_form['descricao'];
				$funcao->carga_horaria = $dados_form['carga_horaria'];
				$funcao->meses = $dados_form['meses'];
				
				
				$valor_inicial = str_replace(['.'],'', $dados_form['valor_inicial']);
    			$valor_inicial = str_replace([','],'.', $valor_inicial);								
				$funcao->valor_inicial = (empty($dados_form['valor_inicial'])) ? NULL : $valor_inicial;

				$valor_medio = str_replace(['.'],'', $dados_form['valor_medio']);
    			$valor_medio = str_replace([','],'.', $valor_medio);				
				$funcao->valor_medio = (empty($dados_form['valor_medio'])) ? NULL : $valor_medio;
				

				$valor_final = str_replace(['.'],'', $dados_form['valor_final']);
    			$valor_final = str_replace([','],'.', $valor_final);				
				$funcao->valor_final = (empty($dados_form['valor_final'])) ? NULL : $valor_final;
				
				
				$funcao->quantidade = $dados_form['quantidade'];
				$funcao->requisitos = $dados_form['requisitos'];


				$funcao_id = $this->getFuncaoTable()->saveFuncao($funcao);

				/* INICIO Grava log */
				$log_acao = "funcao/add";
				$log_acao_id = $funcao_id;
				$log_acao_exibicao = 'Cadastra função';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('funcao');
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
		
		$funcao = $this->getFuncaoTable()->getFuncao($id);

		$request = $this->getRequest();

		if ($request->isPost()) {
			$funcao = new Funcao();
			$dados_form = $request->getPost();

			if ($dados_form) {
				$funcao->id = $id;
				$funcao->descricao = $dados_form['descricao'];
				$funcao->carga_horaria = $dados_form['carga_horaria'];
				$funcao->meses = $dados_form['meses'];
				$funcao->codigo = $dados_form['codigo'];
				
				$valor_inicial = str_replace(['.'],'', $dados_form['valor_inicial']);
    			$valor_inicial = str_replace([','],'.', $valor_inicial);								
				$funcao->valor_inicial = (empty($dados_form['valor_inicial'])) ? NULL : $valor_inicial;

				$valor_medio = str_replace(['.'],'', $dados_form['valor_medio']);
    			$valor_medio = str_replace([','],'.', $valor_medio);				
				$funcao->valor_medio = (empty($dados_form['valor_medio'])) ? NULL : $valor_medio;
				

				$valor_final = str_replace(['.'],'', $dados_form['valor_final']);
    			$valor_final = str_replace([','],'.', $valor_final);				
				$funcao->valor_final = (empty($dados_form['valor_final'])) ? NULL : $valor_final;

				$funcao->quantidade = $dados_form['quantidade'];
				$funcao->requisitos = $dados_form['requisitos'];


				$funcao_id = $this->getFuncaoTable()->saveFuncao($funcao);

				/* INICIO Grava log */
				$log_acao = "funcao/edit";
				$log_acao_id = $funcao_id;
				$log_acao_exibicao = 'Edita função';
				$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
				/* FIM Grava log */

				return $this->redirect()->toRoute('funcao');
			}
			
		}


		return new ViewModel(array(
			'id' => $id,
			'funcao' => $funcao,
		));
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);				

		if ($id) {

			$this->getFuncaoTable()->deleteFuncao($id);

			/* INICIO Grava log */
			$log_acao = "funcao/delete";
			$log_acao_id = $id;
			$log_acao_exibicao = 'Exclui funcao';
			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
			/* FIM Grava log */

			return $this->redirect()->toRoute('funcao', array(
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
