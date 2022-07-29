<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;

use Application\Model\TipoVinculo;
use Application\Model\TipoVinculoTable;

use Application\Model\Escolaridade;
use Application\Model\EscolaridadeTable;


use Application\Model\UsuarioAtividade;
use Application\Model\UsuarioAtividadeTable;

use Application\Model\AtividadeProjetoUsuario;
use Application\Model\AtividadeProjetoUsuarioTable;


use Application\Model\UsuarioProjeto;
use Application\Model\UsuarioProjetoTable;

use Application\Model\CumprimentoObjetoAtividade;
use Application\Model\CumprimentoObjetoAtividadeTable;

use Application\Model\Artefato;
use Application\Model\ArtefatoTable;

use Application\Model\CumprimentoObjeto;
use Application\Model\CumprimentoObjetoTable;

use Application\Model\AtividadePTCProjeto;
use Application\Model\AtividadePTCProjetoTable;

use Application\Model\AtividadePTC;
use Application\Model\AtividadePTCTable;

use Application\Model\AtividadeProjeto;
use Application\Model\AtividadeProjetoTable;

use Application\Model\Representante;
use Application\Model\RepresentanteTable;

use Application\Model\RepresentanteProjeto;
use Application\Model\RepresentanteProjetoTable;

use Application\Model\Projeto;
use Application\Model\ProjetoTable;

use Application\Model\TipoProjeto;
use Application\Model\TipoProjetoTable;

use Application\Model\Area;
use Application\Model\AreaTable;

use Application\Model\Submeta;
use Application\Model\SubmetaTable;

use Application\Model\Nucleo;
use Application\Model\NucleoTable;

use Application\Model\Usuario;
use Application\Model\UsuarioTable;

use Application\Model\Participante;
use Application\Model\ParticipanteTable;

use Application\Model\ParticipanteProjeto;
use Application\Model\ParticipanteProjetoTable;


use Application\Model\PerfilAcesso;
use Application\Model\PerfilAcessoTable;

use Application\Model\Funcionalidade;
use Application\Model\FuncionalidadeTable;

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Application\Model\Log;
use Application\Model\LogTable;

use Application\Model\Ferias;
use Application\Model\FeriasTable;

use Application\Model\RepresentanteUsuario;
use Application\Model\RepresentanteUsuarioTable;

use Application\Model\Setor;
use Application\Model\SetorTable;

use Application\Model\Funcao;
use Application\Model\FuncaoTable;

use Application\Model\AreaFuncao;
use Application\Model\AreaFuncaoTable;

use Application\Model\AreaFuncaoHistorico;
use Application\Model\AreaFuncaoHistoricoTable;

use Application\Model\UsuarioPermissao;
use Application\Model\UsuarioPermissaoTable;

use Application\Model\Bonificacao;
use Application\Model\BonificacaoTable;

use Application\Model\Bolsa;
use Application\Model\BolsaTable;

use Application\Model\DocumentoUsuario;
use Application\Model\DocumentoUsuarioTable;


use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Mvc\Application;

class Module implements AutoloaderProviderInterface
{
	/*
    protected $permissaoTable;
    protected $funcionalidadeTable;

    public function getFuncionalidadeTable()
    {
    	if (!$this->funcionalidadeTable) {
    		$sm = $this->getServiceLocator();
    		$this->funcionalidadeTable = $sm->get('Application\Model\FuncionalidadeTable');
    	}
    	return $this->funcionalidadeTable;
    }
    public function getPermissaoTable()
	{
		if (!$this->permissaoTable) {
			$sm = $this->getServiceLocator();
			$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
		}
		return $this->permissaoTable;
	}
	*/

	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();

		$sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, array($this, 'verificaAutenticacao'), 100);
	}

	public function verificaAutenticacao($e)
	{
		// vamos descobrir onde estamos?
		$controller = $e->getTarget();
		$rota = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
		$sessao = new Container('usuario_dados');
		$action = $controller->getEvent()->getRouteMatch()->getParam('action');

		//    	echo '<pre>';
		//    	print_r($sessao->funcionalidades_usuario);
		//        echo '</pre>';
		//    	die;
		if ($rota != 'login' && $rota != 'login/default' && $rota != 'login/recuperarsenha' && $rota != 'acesso-negado') {

			if (is_array($sessao->funcionalidades_usuario)) {
				if (!in_array($rota, $sessao->funcionalidades_usuario)) {
					return $controller->redirect()->toRoute('acesso-negado');
				}
			} else {
				return $controller->redirect()->toRoute('login');
			}

			if (!$sessao->id) {
				return $controller->redirect()->toRoute('login');
			}
		}
	}

	public function onBootstrap(MvcEvent $e)
	{
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		$eventManager->attach('route', function (MvcEvent $mvcEvent) {
			$params = $mvcEvent->getRouteMatch()->getParams();

			foreach ($params as $name => $value) {
				if (!isset($_GET[$name])) {
					$_GET[$name] = $value;
				}
			}
		});

		$e->getApplication()->getEventManager()->getSharedManager()->attach(
			'Zend\Mvc\Controller\AbstractActionController',
			'dispatch',
			function ($e) {
				$controller = $e->getTarget();
				$controllerClass = get_class($controller);
				$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
				$config = $e->getApplication()->getServiceManager()->get('config');
				if (isset($config['module_layouts'][$moduleNamespace])) {
					$controller->layout($config['module_layouts'][$moduleNamespace]);
				}
			},
			100
		);

		/*
        	$serviceManager = $e->getApplication()->getServiceManager();
        	$viewModel = $e->getApplication()->getMvcEvent()->getViewModel();

        	//$helper = new Helper($serviceManager);
			//$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');

        	//$viewModel->someVar = $this->getPermissaoTable()->fetchAll();
        	 * */
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					// if we're in a namespace deeper than one level we need to fix the \ in the path
					__NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
				),
			),
		);
	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(

				'Application\Model\AssociadoTable' =>  function ($sm) {
					$tableGateway = $sm->get('AssociadoTableGateway');
					$table = new AssociadoTable($tableGateway);
					return $table;
				},

				'Application\Model\RepresentanteTable' =>  function ($sm) {
					$tableGateway = $sm->get('RepresentanteTableGateway');
					$table = new RepresentanteTable($tableGateway);
					return $table;
				},
				'RepresentanteTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Representante());
					return new TableGateway('representante_tce', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\RepresentanteUsuarioTable' =>  function ($sm) {
					$tableGateway = $sm->get('RepresentanteUsuarioTableGateway');
					$table = new RepresentanteUsuarioTable($tableGateway);
					return $table;
				},
				'RepresentanteUsuarioTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new RepresentanteUsuario());
					return new TableGateway('representante_usuario', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\ParticipanteTable' =>  function ($sm) {
					$tableGateway = $sm->get('ParticipanteTableGateway');
					$table = new ParticipanteTable($tableGateway);
					return $table;
				},

				'ParticipanteTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Participante());
					return new TableGateway('participantes', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\ParticipanteProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('ParticipanteProjetoTableGateway');
					$table = new ParticipanteProjetoTable($tableGateway);
					return $table;
				},

				'ParticipanteProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new ParticipanteProjeto());
					return new TableGateway('participante_projeto', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\AreaTable' =>  function ($sm) {
					$tableGateway = $sm->get('AreaTableGateway');
					$table = new AreaTable($tableGateway);
					return $table;
				},
				'AreaTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Area());
					return new TableGateway('area', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\SubmetaTable' =>  function ($sm) {
					$tableGateway = $sm->get('SubmetaTableGateway');
					$table = new SubmetaTable($tableGateway);
					return $table;
				},
				'SubmetaTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Submeta());
					return new TableGateway('submeta', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\NucleoTable' =>  function ($sm) {
					$tableGateway = $sm->get('NucleoTableGateway');
					$table = new NucleoTable($tableGateway);
					return $table;
				},
				'NucleoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Nucleo());
					return new TableGateway('nucleo', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\UsuarioTable' =>  function ($sm) {
					$tableGateway = $sm->get('UsuarioTableGateway');
					$table = new UsuarioTable($tableGateway);
					return $table;
				},
				'UsuarioTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Usuario());
					return new TableGateway('usuario', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\PerfilAcessoTable' =>  function ($sm) {
					$tableGateway = $sm->get('PerfilAcessoTableGateway');
					$table = new PerfilAcessoTable($tableGateway);
					return $table;
				},
				'PerfilAcessoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new PerfilAcesso());
					return new TableGateway('perfil_acesso', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\FuncionalidadeTable' =>  function ($sm) {
					$tableGateway = $sm->get('FuncionalidadeTableGateway');
					$table = new FuncionalidadeTable($tableGateway);
					return $table;
				},
				'FuncionalidadeTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Funcionalidade());
					return new TableGateway('funcionalidade', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\PermissaoTable' =>  function ($sm) {
					$tableGateway = $sm->get('PermissaoTableGateway');
					$table = new PermissaoTable($tableGateway);
					return $table;
				},
				'PermissaoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Permissao());
					return new TableGateway('permissao', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\TipoProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('TipoProjetoTableGateway');
					$table = new TipoProjetoTable($tableGateway);
					return $table;
				},
				'TipoProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new TipoProjeto());
					return new TableGateway('tipo_projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\ProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('ProjetoTableGateway');
					$table = new ProjetoTable($tableGateway);
					return $table;
				},
				'ProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Projeto());
					return new TableGateway('projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\RepresentanteProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('RepresentanteProjetoTableGateway');
					$table = new RepresentanteProjetoTable($tableGateway);
					return $table;
				},
				'RepresentanteProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new RepresentanteProjeto());
					return new TableGateway('representante_tce_projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\AtividadeProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('AtividadeProjetoTableGateway');
					$table = new AtividadeProjetoTable($tableGateway);
					return $table;
				},
				'AtividadeProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AtividadeProjeto());
					return new TableGateway('atividade_projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\AtividadePTCTable' =>  function ($sm) {
					$tableGateway = $sm->get('AtividadePTCTableGateway');
					$table = new AtividadePTCTable($tableGateway);
					return $table;
				},
				'AtividadePTCTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AtividadePTC());
					return new TableGateway('atividade_ptc', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\AtividadePTCProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('AtividadePTCProjetoTableGateway');
					$table = new AtividadePTCProjetoTable($tableGateway);
					return $table;
				},
				'AtividadePTCProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AtividadePTCProjeto());
					return new TableGateway('atividade_ptc_atividade_projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\CumprimentoObjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('CumprimentoObjetoTableGateway');
					$table = new CumprimentoObjetoTable($tableGateway);
					return $table;
				},
				'CumprimentoObjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CumprimentoObjeto());
					return new TableGateway('relatorio_cumprimento_objeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\ArtefatoTable' =>  function ($sm) {
					$tableGateway = $sm->get('ArtefatoTableGateway');
					$table = new ArtefatoTable($tableGateway);
					return $table;
				},
				'ArtefatoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Artefato());
					return new TableGateway('artefato', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\CumprimentoObjetoAtividadeTable' =>  function ($sm) {
					$tableGateway = $sm->get('CumprimentoObjetoAtividadeTableGateway');
					$table = new CumprimentoObjetoAtividadeTable($tableGateway);
					return $table;
				},
				'CumprimentoObjetoAtividadeTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CumprimentoObjetoAtividade());
					return new TableGateway('relatorio_cumprimento_objeto_atividade', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\UsuarioProjetoTable' =>  function ($sm) {
					$tableGateway = $sm->get('UsuarioProjetoTableGateway');
					$table = new UsuarioProjetoTable($tableGateway);
					return $table;
				},
				'UsuarioProjetoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new UsuarioProjeto());
					return new TableGateway('usuario_projeto', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\UsuarioAtividadeTable' =>  function ($sm) {
					$tableGateway = $sm->get('UsuarioAtividadeTableGateway');
					$table = new UsuarioAtividadeTable($tableGateway);
					return $table;
				},
				'UsuarioAtividadeTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new UsuarioAtividade());
					return new TableGateway('usuario_atividade', $dbAdapter, null, $resultSetPrototype);
				},
				'Application\Model\TipoVinculoTable' =>  function ($sm) {
					$tableGateway = $sm->get('TipoVinculoTableGateway');
					$table = new TipoVinculoTable($tableGateway);
					return $table;
				},
				'TipoVinculoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new TipoVinculo());
					return new TableGateway('tipo_vinculo', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\EscolaridadeTable' =>  function ($sm) {
					$tableGateway = $sm->get('EscolaridadeTableGateway');
					$table = new EscolaridadeTable($tableGateway);
					return $table;
				},
				'EscolaridadeTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Escolaridade());
					return new TableGateway('escolaridade', $dbAdapter, null, $resultSetPrototype);
				},

				'Application\Model\AtividadeProjetoUsuarioTable' =>  function ($sm) {
					$tableGateway = $sm->get('AtividadeProjetoUsuarioTableGateway');
					$table = new AtividadeProjetoUsuarioTable($tableGateway);
					return $table;
				},
				'AtividadeProjetoUsuarioTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AtividadeProjetoUsuarioTable());
					return new TableGateway('atividade_projeto_usuario', $dbAdapter, null, $resultSetPrototype);
				},

				//log
				'Application\Model\LogTable' =>  function ($sm) {
					$tableGateway = $sm->get('LogTableGateway');
					$table = new LogTable($tableGateway);
					return $table;
				},
				'LogTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Log());
					return new TableGateway('log', $dbAdapter, null, $resultSetPrototype);
				},


				//Setor
				'Application\Model\SetorTable' =>  function ($sm) {
					$tableGateway = $sm->get('SetorTableGateway');
					$table = new SetorTable($tableGateway);
					return $table;
				},
				'SetorTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Setor());
					return new TableGateway('setor', $dbAdapter, null, $resultSetPrototype);
				},

				//Férias
				'Application\Model\FeriasTable' =>  function ($sm) {
					$tableGateway = $sm->get('FeriasTableGateway');
					$table = new FeriasTable($tableGateway);
					return $table;
				},
				'FeriasTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Ferias());
					return new TableGateway('ferias', $dbAdapter, null, $resultSetPrototype);
				},

				//Funcao
				'Application\Model\FuncaoTable' =>  function ($sm) {
					$tableGateway = $sm->get('FuncaoTableGateway');
					$table = new FuncaoTable($tableGateway);
					return $table;
				},
				'FuncaoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Funcao());
					return new TableGateway('funcao', $dbAdapter, null, $resultSetPrototype);
				},

				//AreaFuncao
				'Application\Model\AreaFuncaoTable' =>  function ($sm) {
					$tableGateway = $sm->get('AreaFuncaoTableGateway');
					$table = new AreaFuncaoTable($tableGateway);
					return $table;
				},
				'AreaFuncaoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AreaFuncao());
					return new TableGateway('area_funcao', $dbAdapter, null, $resultSetPrototype);
				},

				//AreaFuncaoHistorico
				'Application\Model\AreaFuncaoHistoricoTable' =>  function ($sm) {
					$tableGateway = $sm->get('AreaFuncaoHistoricoTableGateway');
					$table = new AreaFuncaoHistoricoTable($tableGateway);
					return $table;
				},
				'AreaFuncaoHistoricoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new AreaFuncaoHistorico());
					return new TableGateway('area_funcao_historico', $dbAdapter, null, $resultSetPrototype);
				},

				//Usuario Permissao
				'Application\Model\UsuarioPermissaoTable' =>  function ($sm) {
					$tableGateway = $sm->get('UsuarioPermissaoTableGateway');
					$table = new UsuarioPermissaoTable($tableGateway);
					return $table;
				},
				'UsuarioPermissaoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new UsuarioPermissao());
					return new TableGateway('usuario_permissao', $dbAdapter, null, $resultSetPrototype);
				},


				//Bonificacao
				'Application\Model\BonificacaoTable' =>  function ($sm) {
					$tableGateway = $sm->get('BonificacaoTableGateway');
					$table = new BonificacaoTable($tableGateway);
					return $table;
				},
				'BonificacaoTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Bonificacao());
					return new TableGateway('bonificacao', $dbAdapter, null, $resultSetPrototype);
				},

				//DocumentoUsuario
				'Application\Model\DocumentoUsuarioTable' =>  function ($sm) {
					$tableGateway = $sm->get('DocumentoUsuarioTableGateway');
					$table = new DocumentoUsuarioTable($tableGateway);
					return $table;
				},
				'DocumentoUsuarioTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new DocumentoUsuario());
					return new TableGateway('documentos_usuario', $dbAdapter, null, $resultSetPrototype);
				},


				//Bolsa
				'Application\Model\BolsaTable' =>  function ($sm) {
					$tableGateway = $sm->get('BolsaTableGateway');
					$table = new BolsaTable($tableGateway);
					return $table;
				},
				'BolsaTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Bolsa());
					return new TableGateway('bolsa', $dbAdapter, null, $resultSetPrototype);
				},




			),
		);
	}
}
