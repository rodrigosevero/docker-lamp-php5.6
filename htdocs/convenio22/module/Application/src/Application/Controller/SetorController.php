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

use Application\Model\SetorTable;
use Application\Model\Setor;

use Application\Model\LogTable;
use Application\Model\Log;

class SetorController extends AbstractActionController
{
    protected $setorTable;
    protected $logtable;

	public function getLogTable()
	{
		if (!$this->logTable) {
			$sm = $this->getServiceLocator();
			$this->logTable = $sm->get('Application\Model\LogTable');
		}
		return $this->logTable;
	}

	public function getSetorTable()
	{
		if (!$this->setorTable) {
			$sm = $this->getServiceLocator();
			$this->setorTable = $sm->get('Application\Model\SetorTable');
		}
		return $this->setorTable;
	}



    public function indexAction()
    {


    	return new ViewModel(array(
        'setores' => $this->getSetorTable()->getSetoresAtivos(),
       )
      );
    }

  public function addAction()
	{
		$request = $this->getRequest();

		if ($request->isPost()) {
			$setor = new Setor();
			$dados_form = $request->getPost();

			if ($dados_form) {

				$setor->descricao= $dados_form['descricao'];
				$setor->del= 0;

				$setor_id = $this->getSetorTable()->saveSetor($setor);

        /* INICIO Grava log */
    			$log_acao = "setor/add";
    			$log_acao_id = $usuario_id;
    			$log_acao_exibicao = 'Cadastra de setor';
    			$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
    			/* FIM Grava log */

				return $this->redirect()->toRoute('setor');
			}
		}

		return new ViewModel(array(
    			'setor' => $this->getSetorTable()->getSetoresAtivos(),
		));
	}
	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$setor = $this->getSetorTable()->getSetor($id);

		$request = $this->getRequest();

		if ($request->isPost()) {
			$dados_form = $request->getPost();

			if ($dados_form) {

				$setor->descricao = $dados_form['descricao'];
				$setor->del = 0;
				$this->getSetorTable()->saveSetor($setor);

		    	/* INICIO Grava log */
		    	$log_acao = "setor/edit";
		    	$log_acao_id = $setor->id;
		    	$log_acao_exibicao = 'Edita setor';
		    	$this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
		    	/* FIM Grava log */

				return $this->redirect()->toRoute('setor');
			}
		}

		return array(
				'id' => $id,
				'setor' => $setor,
		);
	}


  public function deleteAction()
  {
      $id = (int) $this->params()->fromRoute('id', 0);


      if ($id) {

          $this->getSetorTable()->deleteSetor($id);

          /* INICIO Grava log */
          $log_acao = "setor/delete";
          $log_acao_id = $id;
          $log_acao_exibicao = 'Exclui setor';
          $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
          /* FIM Grava log */

          return $this->redirect()->toRoute('setor');
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
