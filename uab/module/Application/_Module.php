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


use Application\Model\PerfilAcesso;
use Application\Model\PerfilAcessoTable;

use Application\Model\Funcionalidade;
use Application\Model\FuncionalidadeTable;

use Application\Model\Permissao;
use Application\Model\PermissaoTable;

use Application\Model\Log;
use Application\Model\LogTable;

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
		/*
    	echo '<pre>';
    	print_r($sessao->funcionalidades_usuario);
    	*/
    	if ($rota != 'login' && $rota != 'login/default') {

    		if(is_array($sessao->funcionalidades_usuario)){
	    		 if(!in_array($rota, $sessao->funcionalidades_usuario)){
	    		 	return $controller->redirect()->toRoute('home');
	    		 }
    		}else{
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
        
        $eventManager->attach('route', function(MvcEvent $mvcEvent) {
        	$params = $mvcEvent->getRouteMatch()->getParams();
        
        	foreach ( $params as $name => $value )
        	{
        		if ( ! isset($_GET[$name]))
        		{
        			$_GET[$name] = $value;
        		}
        	}
        });
        
        	$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e)
        	{
        		$controller = $e->getTarget();
        		$controllerClass = get_class($controller);
        		$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        		$config = $e->getApplication()->getServiceManager()->get('config');
        		if (isset($config['module_layouts'][$moduleNamespace])) {
        			$controller->layout($config['module_layouts'][$moduleNamespace]);
        		}
        	}
        	, 100);
        	
/*
        	$serviceManager = $e->getApplication()->getServiceManager();
        	$viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        	
        	//$helper = new Helper($serviceManager);
			//$this->permissaoTable = $sm->get('Application\Model\PermissaoTable');
        	
        	//$viewModel->someVar = $this->getPermissaoTable()->fetchAll();
        	 * */
        	 
    }
	
	 public function setBaseUrl(MvcEvent $e) {
        $request = $e->getRequest();
        $baseUrl = $request->getServer('APPLICATION_BASEURL');

        if (!empty($baseUrl) && $request->getServer('HTTP_X_FORWARDED_FOR', false)) {
            $router = $e->getApplication()->getServiceManager()->get('Router');
            $router->setBaseUrl($baseUrl);
            $request->setBaseUrl($baseUrl);
        }
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
    							__NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
    					),
    			),
    	);
    }
    
    public function getServiceConfig()
    {        
        return array(
        		'factories' => array(

        				/*

        				'myviewhelper' => function ($sm) {
        				return new Application\Controller\Helper\Helper($sm);
        				},
        				
        				'AbcService' => function($sm) {
        				$abcTable = $sm->get('Application\Model\PermissaoTable');
        				
        				$abcService = new AbcService($abcTable);
        				
        				return $abcService;
        				},
        				 * 
            'my-model-gateway' => function($sm) {
               $gw = new MyTableGateway($sm->get('Zend\Db\Adapter\Adapter'));
               return $gw;
            }
            $gw = $this->getServiceLocator()->get('my-model-gateway');
        				
        				'Application\Model\MyAuthStorage' => function($sm){
        				return new \Application\Model\MyAuthStorage('controle_gastos');
        				},
        				 
        				'AuthService' => function($sm) {
        					//My assumption, you've alredy set dbAdapter
        					//and has users table with columns : user_name and pass_word
        					//that password hashed with md5
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
        							#'users','username','password', 'MD5(?)');
        							'users','username','password');
        				
        					$authService = new AuthenticationService();
        					$authService->setAdapter($dbTableAuthAdapter);
        					$authService->setStorage($sm->get('Application\Model\MyAuthStorage'));
        				
        					return $authService;
        				},
        				* */
        				
						'Application\Model\AssociadoTable' =>  function($sm) {
        				$tableGateway = $sm->get('AssociadoTableGateway');
        				$table = new AssociadoTable($tableGateway);
        				return $table;
        				},       	
        				
						'Application\Model\RepresentanteTable' =>  function($sm) {
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
						
						'Application\Model\ParticipanteTable' =>  function($sm) {
        				$tableGateway = $sm->get('ParticipanteTableGateway');
        				$table = new ParticipanteTable($tableGateway);
        				return $table;
        				},
        				'ParticipanteTableGateway' => function ($sm) {
        				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        				$resultSetPrototype = new ResultSet();
        				$resultSetPrototype->setArrayObjectPrototype(new Representante());
        				return new TableGateway('participantes', $dbAdapter, null, $resultSetPrototype);
        				},  	
        				
						'Application\Model\AreaTable' =>  function($sm) {
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
        				'Application\Model\SubmetaTable' =>  function($sm) {
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
        				'Application\Model\NucleoTable' =>  function($sm) {
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
        				'Application\Model\UsuarioTable' =>  function($sm) {
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
        				'Application\Model\PerfilAcessoTable' =>  function($sm) {
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
        				'Application\Model\FuncionalidadeTable' =>  function($sm) {
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
        				'Application\Model\PermissaoTable' =>  function($sm) {
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
        				'Application\Model\TipoProjetoTable' =>  function($sm) {
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
        				'Application\Model\ProjetoTable' =>  function($sm) {
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
        				'Application\Model\RepresentanteProjetoTable' =>  function($sm) {
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
        				'Application\Model\AtividadeProjetoTable' =>  function($sm) {
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
        				'Application\Model\AtividadePTCTable' =>  function($sm) {
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
        				'Application\Model\AtividadePTCProjetoTable' =>  function($sm) {
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
        				'Application\Model\CumprimentoObjetoTable' =>  function($sm) {
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
        				'Application\Model\ArtefatoTable' =>  function($sm) {
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
        				'Application\Model\CumprimentoObjetoAtividadeTable' =>  function($sm) {
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
        				'Application\Model\UsuarioProjetoTable' =>  function($sm) {
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
        				'Application\Model\UsuarioAtividadeTable' =>  function($sm) {
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
        				'Application\Model\TipoVinculoTable' =>  function($sm) {
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
						'Application\Model\EscolaridadeTable' =>  function($sm) {
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
    					
    					//log
    					'Application\Model\LogTable' =>  function($sm) {
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
        		),
        );
    }
}
