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

use Application\Model\LogTable;
use Application\Model\Log;

use Application\Model\UsuarioTable;
use Application\Model\Usuario;

use Application\Model\FeriasTable;
use Application\Model\Ferias;

class FeriasController extends AbstractActionController
{
    protected $areaTable;
    protected $submetaTable;
    protected $nucleoTable;
    protected $logTable;
    protected $feriasTable;
    protected $usuarioTable;


    public function getLogTable()
    {
        if (!$this->logTable) {
            $sm = $this->getServiceLocator();
            $this->logTable = $sm->get('Application\Model\LogTable');
        }
        return $this->logTable;
    }

    public function getFeriasTable()
    {
        if (!$this->feriasTable) {
            $sm = $this->getServiceLocator();
            $this->feriasTable = $sm->get('Application\Model\FeriasTable');
        }
        return $this->feriasTable;
    }

    public function getUsuarioTable()
    {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
        }
        return $this->usuarioTable;
    }


    public function indexAction()
    {

        $ferias_ativas = $this->getFeriasTable()->getFeriasAtivas();

        /* INICIO Grava log */
        $log_acao = "ferias";
        $log_acao_id = NULL;
        $log_acao_exibicao = 'Consulta férias';
        $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
        /* FIM Grava log */

        return new ViewModel(array('colaboradores' => $ferias_ativas));
    }
    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $ferias = new Ferias();
            $dados_form = $request->getPost();

            if ($dados_form) {

                $ferias->usuario_id= $dados_form['usuario_id'];
                $ferias->inicio= $dados_form['inicio'];
                $ferias->fim= $dados_form['fim'];
                $ferias->pa_inicio= $dados_form['pa_inicio'];
                $ferias->pa_fim= $dados_form['pa_fim'];
                $ferias->del= 0;
//                echo '<pre>';
//                print_r($ferias);
//                echo '</pre>';
//                die;
                $this->getFeriasTable()->saveFerias($ferias);

                /* INICIO Grava log */
                $log_acao = "ferias/add";
                $log_acao_id = $dados_form['usuario_id'];
                $log_acao_exibicao = 'Cadastra férias';
                $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
                /* FIM Grava log */

                return $this->redirect()->toRoute('ferias');
            }
        }

        return new ViewModel(array(
            'colaboradores' => $this->getUsuarioTable()->getUsuariosAtivos(),
        ));
    }
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('area', array(
                'action' => 'index'
            ));
        }

        try {
            $area = $this->getAreaTable()->getArea($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('area', array(
                'action' => 'index'
            ));
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $dados_form = $request->getPost();

            if ($dados_form) {

                $area->descricao = $dados_form['descricao'];
                $area->del = 0;
                $this->getAreaTable()->saveArea($area);

                /* INICIO Grava log */
                $log_acao = "area/edit";
                $log_acao_id = $area->id;
                $log_acao_exibicao = 'Edita área';
                $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
                /* FIM Grava log */

                return $this->redirect()->toRoute('area');
            }
        }

        return array(
            'id' => $id,
            'area' => $area,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('ferias');
        }

        if ($id) {

            $this->getFeriasTable()->deleteFerias($id);

            /* INICIO Grava log */
            $log_acao = "ferias/delete";
            $log_acao_id = $id;
            $log_acao_exibicao = 'Exclui ferias';
            $this->salvarLog($log_acao, $log_acao_id, $log_acao_exibicao);
            /* FIM Grava log */

            return $this->redirect()->toRoute('ferias');
        }

        return array(
            'id'    => $id,
            'colaborador' => $this->getUsuarioTable()->getUsuario($id)
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
