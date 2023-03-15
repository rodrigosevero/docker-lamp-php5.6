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

use Application\Model\UsuarioTable;
use Application\Model\Usuario;

use Application\Model\PerfilAcessoTable;
use Application\Model\PerfilAcesso;

use Application\Model\Funcionalidade;
use Application\Model\FuncionalidadeTable;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class LoginController extends AbstractActionController
{		
    public function indexAction()
    {    	 
    	// Turn off the layout, i.e. only render the view script.
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
    
    protected $usuarioTable;    
    protected $perfilAcessoTable;
    protected $funcionalidadeTable;
    
	public function getUsuarioTable()
	{
		if (!$this->usuarioTable) {
			$sm = $this->getServiceLocator();
			$this->usuarioTable = $sm->get('Application\Model\UsuarioTable');
		}
		return $this->usuarioTable;
	}		
    
	public function getPerfilAcessoTable()
	{
		if (!$this->perfilAcessoTable) {
			$sm = $this->getServiceLocator();
			$this->perfilAcessoTable = $sm->get('Application\Model\PerfilAcessoTable');
		}
		return $this->perfilAcessoTable;
	}		
    
	public function getFuncionalidadeTable()
	{
		if (!$this->funcionalidadeTable) {
			$sm = $this->getServiceLocator();
			$this->funcionalidadeTable = $sm->get('Application\Model\FuncionalidadeTable');
		}
		return $this->funcionalidadeTable;
	}		
	
	function enviaEmail($msg, $assunto, $destinatario) {
        $options   = new SmtpOptions(array(
            'name'              => 'localhost.localdomain',
            'host'              => 'mx.setec.ufmt.br',
            'port'              => '587',
            'connection_class'  => 'plain',
            'connection_config' => array(
                'username' => 'naoresponder@setec.ufmt.br',
                'password' => 'respondernao321',
                'ssl' => 'tls'
            ),
        ));
		$mail = new Mail\Message();
		$mail->setBody($msg);
		$mail->setFrom('naoresponder@setec.ufmt.br', 'UFMT - SISTEMA DE PROJETOS - UAB');
		$mail->addTo($destinatario);
		//$mail->addCC( 'ao@gmail.com' );
		$mail->setSubject($assunto);
		$transport = new SmtpTransport();
		$transport->setOptions( $options );
		$transport->send($mail);
    }
	
	function geraSenha($tamanho = 8, $maiusculas = false, $numeros = true, $simbolos = false)
	{
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;
		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
		}
	return $retorno;
	}
    
    public function recuperarsenhaAction()
	{
	
		$request = $this->getRequest();
		
    	if ($request->isPost()) {
    		
    		$form_dados = $request->getPost();
    		 
    		if ($form_dados) {
					
				
				$cpf = str_replace('.', '', $form_dados['cpf']);
				$cpf = str_replace('-', '', $form_dados['cpf']);
				
    			$usuario = $this->getUsuarioTable()->getUsuarioCpf($form_dados['cpf']);
				
				
    			$senha = $this->geraSenha(6);
				$msg = 'Sua nova senha de acesso ao sistema é: '.$senha.'';				
    			if(is_object($usuario)){
					
					$colaborador = new Usuario();
					$colaborador->id = $usuario->id;
					$colaborador->nome = $usuario->nome;
					$i = explode(" ", $usuario->nome);
					$sobrenome = $i[1];
					$colaborador->email = $usuario->email;
					$colaborador->tel_fixo = $usuario->tel_fixo;
					$colaborador->tel_movel = $usuario->tel_movel;
					$colaborador->senha = md5($senha);
					$colaborador->cpf = $usuario->cpf;
					$colaborador->razao_social = $usuario->razao_social;
					$colaborador->cnpj = $usuario->cnpj;
					$colaborador->area_id = $usuario->area_id;
					$colaborador->vinculo = $usuario->vinculo;
					$colaborador->instituicao = $usuario->instituicao;
					$colaborador->cargo_funcao = $usuario->cargo_funcao;
					$colaborador->unidade_lotacao = $usuario->unidade_lotacao;
                    $colaborador->representante_id = $usuario->representante_id;
					$colaborador->status_enquadramento_funcional= $usuario->status_enquadramento_funcional;
					$colaborador->funcao= $usuario->funcao;
					$colaborador->permissao = $usuario->permissao;
					$colaborador->data_admissao = date('Y-m-d', strtotime($usuario->data_admissao));
					$colaborador->del = $usuario->del;
					$colaborador->atualizado = $usuario->atualizado;
					$colaborador->data_atualizado = $usuario->data_atualizado;
					$colaborador->hora_atualizado = $usuario->hora_atualizado;
					$colaborador->status = $usuario->status;
					$colaborador->data_inatividade = $usuario->data_inatividade;
					$colaborador->superadmin = $usuario->superadmin;
					
					$usuario_id = $this->getUsuarioTable()->saveUsuario($colaborador);
    				$this->enviaEmail($msg, "Recuperacao de senha - UFMT - TCE",  $usuario->email);
    				return $this->redirect()->toRoute('login', array(
    						'action' => 'recuperarsenha',
    						'erro'=>'2'
    				));
    			}else{
    				return $this->redirect()->toRoute('login', array(
    						'action' => 'recuperarsenha',
    						'erro'=>'1'
    				));
    			}
    			
				return $this->redirect()->toRoute('home');				
    		}
    	} 			
    	
    	
    	// Turn off the layout, i.e. only render the view script.
    	$viewModel = new ViewModel();
    	$viewModel->setTerminal(true);
    	
	    return $viewModel;
		
	}
    
	
	public function logarAction()
    {
    	$request = $this->getRequest();
    	 
    	if ($request->isPost()) {
    	
    		$form_dados = $request->getPost();
    		 
    		if ($form_dados) {
					
    			$usuarioLogado = $this->getUsuarioTable()->getUsuarioLogin($form_dados['cpf'], md5($form_dados['senha']));

    			if(is_object($usuarioLogado)){
    				$this->criarSessao($usuarioLogado);
    				 
    				return $this->redirect()->toRoute('home');				
    			}else{
    				return $this->redirect()->toRoute('login', array('erro'=>'1'));
    			}
    		
    		}
    	} 	
    }
    
    public function initSession($config)
    {
    	$sessionConfig = new SessionConfig();
    	$sessionConfig->setOptions($config);
    	$sessionManager = new SessionManager($sessionConfig);
    	$sessionManager->start();
    	Container::setDefaultManager($sessionManager);
    }
    
    public function criarSessao($usuarioLogado)
    {	
    	$this->initSession(array(
    			'cookie_lifetime' => 1209600, 
    			'remember_me_seconds' => 1209600,
    			'use_cookies' => true,
    			'cookie_httponly' => true,
    	));
    	
    	$sessionTimer = new Container('usuario_dados');
    	$sessionTimer->id = $usuarioLogado->id;
    	$sessionTimer->nome = $usuarioLogado->nome;
    	$sessionTimer->email = $usuarioLogado->email;
    	$sessionTimer->tel_fixo = $usuarioLogado->tel_fixo;
    	$sessionTimer->tel_movel = $usuarioLogado->tel_movel;
    	$sessionTimer->cpf = $usuarioLogado->cpf;
    	$sessionTimer->cnpj = $usuarioLogado->cnpj;
    	$sessionTimer->razao_social = $usuarioLogado->razao_social;
    	$sessionTimer->area_id = $usuarioLogado->area_id;
    	$sessionTimer->vinculo = $usuarioLogado->vinculo;
    	$sessionTimer->instituicao = $usuarioLogado->instituicao;
    	$sessionTimer->cargo_funcao = $usuarioLogado->cargo_funcao;
    	$sessionTimer->unidade_lotacao = $usuarioLogado->unidade_lotacao;
    	$sessionTimer->permissao = $usuarioLogado->permissao;
    	$sessionTimer->del = $usuarioLogado->del;
    	$sessionTimer->atualizado = $usuarioLogado->atualizado;
    	$sessionTimer->data_atualizado = $usuarioLogado->data_atualizado;
    	$sessionTimer->hora_atualizado = $usuarioLogado->hora_atualizado;
    	$sessionTimer->status = $usuarioLogado->status;
    	$sessionTimer->data_inatividade = $usuarioLogado->data_inatividade;
    	$sessionTimer->data_admissao = $usuarioLogado->data_admissao;
    	$sessionTimer->representante_id = $usuarioLogado->representante_id;
    	$sessionTimer->superadmin = $usuarioLogado->superadmin;

    	$perfilAcessos = $this->getPerfilAcessoTable()->getPerfilAcessos($usuarioLogado->permissao);
    	$funcionalidades = $this->getFuncionalidadeTable()->fetchAll();
    	    	
    	$array_funcionalidades_usuario_logado = Array();
    	 
    	$funcionalidades_nome = Array();
    	 
    	foreach ($funcionalidades as $funcionalidade){
    		$funcionalidades_nome[$funcionalidade->funcionalidade_id] = $funcionalidade->funcionalidade_nome;
    	}
    	 
    	foreach ($perfilAcessos as $acesso){
    		$array_funcionalidades_usuario_logado[$acesso->funcionalidade_id] = $funcionalidades_nome[$acesso->funcionalidade_id];
    	}
    	 
    	$array_funcionalidades_usuario_logado['1'] = 'home';
    	$array_funcionalidades_usuario_logado['2'] = 'login';
    	 
    	$sessionTimer->funcionalidades_usuario = $array_funcionalidades_usuario_logado;
    }

	public function atualizarDadosSessao($usuario_dados)
	{
		$dados_sessao_atual = new Container('usuario_dados');

		$dados_sessao_atual->id = $usuario_dados->id;
		$dados_sessao_atual->email = $usuario_dados->email;
		$dados_sessao_atual->nome = $usuario_dados->nome;		
    	$dados_sessao_atual->tel_fixo = $usuario_dados->tel_fixo;
    	$dados_sessao_atual->tel_movel = $usuario_dados->tel_movel;
    	$dados_sessao_atual->cpf = $usuario_dados->cpf;
    	$dados_sessao_atual->cnpj = $usuario_dados->cnpj;
    	$dados_sessao_atual->razao_social = $usuario_dados->razao_social;
    	$dados_sessao_atual->area_id = $usuario_dados->area_id;
    	$dados_sessao_atual->vinculo = $usuario_dados->vinculo;
    	$dados_sessao_atual->instituicao = $usuario_dados->instituicao;
    	$dados_sessao_atual->cargo_funcao = $usuario_dados->cargo_funcao;
    	$dados_sessao_atual->unidade_lotacao = $usuario_dados->unidade_lotacao;
    	$dados_sessao_atual->permissao = $usuario_dados->permissao;
    	$dados_sessao_atual->del = $usuario_dados->del;
    	$dados_sessao_atual->atualizado = $usuario_dados->atualizado;
    	$dados_sessao_atual->data_atualizado = $usuario_dados->data_atualizado;
    	$dados_sessao_atual->hora_atualizado = $usuario_dados->hora_atualizado;
    	$dados_sessao_atual->status = $usuario_dados->status;
    	$dados_sessao_atual->data_inatividade = $usuario_dados->data_inatividade;
    	$dados_sessao_atual->data_admissao = $usuario_dados->data_admissao;
    	$dados_sessao_atual->superadmin = $usuario_dados->superadmin;
	}
	

	public function setRememberMe($rememberMe, $time = 1209600)
	{
    	$usuario_dados = new Container('usuario_dados');
    	
		if ($rememberMe == 1) {
			$usuario_dados->getManager()->rememberMe($time);
		}
	}
	
	public function changePasswordAction()
	{
		$request = $this->getRequest();
		$session_dados = new Container('usuario_dados');
		$id = $session_dados->id;
	
		if ($request->isPost()) {
	
			$form_dados = $request->getPost();
	
			if ($form_dados) {
				$usuario = $this->getUsuarioTable()->getUsuario($id);

				if(count($usuario) > 0){
											
					$usuario->senha = md5($form_dados['senha1']);
						
					$this->getUsuarioTable()->saveUsuario($usuario);
	
					return $this->redirect()->toRoute('home');
				}else{
					return $this->redirect()->toRoute('login', array(
							'action' => 'index',
							'erro'=>'1'
					));
				}
	
			}
		}
	}
	
	public function meusDadosAction()
	{
		$request = $this->getRequest();
		$session_dados = new Container('usuario_dados');
		$id = $session_dados->id;
	
		if ($request->isPost()) {
	
			$form_dados = $request->getPost();
	
			if ($form_dados) {
				$usuario = $this->getUsuarioTable()->getUsuario($id);

				if(count($usuario) > 0){
						
					$usuario->nome = utf8_encode($form_dados['nome']);
					$usuario->email =  $form_dados['email'];
					$usuario->tel_fixo =  $form_dados['tel_fixo'];
					$usuario->cpf = $form_dados['cpf'];
					$usuario->cnpj =  $form_dados['cnpj'];
					$usuario->razao_social = $form_dados['razao_social'];
					$usuario->instituicao = $form_dados['instituicao'];
					$usuario->cargo_funcao = $form_dados['cargo_funcao'];
					$usuario->superadmin= '0';	
					$this->getUsuarioTable()->saveUsuario($usuario);
					$this->atualizarDadosSessao($usuario);
	
					return $this->redirect()->toRoute('home');
				}else{
					return $this->redirect()->toRoute('login', array(
							'action' => 'index',
							'erro'=>'1'
					));
				}
	
			}
		}
	}
	
	public function forgetAction()
	{
		$request = $this->getRequest();
    	 
    	if ($request->isPost()) {
    	
    		$form_dados = $request->getPost();
    		 
    		if ($form_dados) {
					
    			$usuarioLogado = $this->getUsuarioTable()->getUsuarioEmail($form_dados['email']);

    			if(is_array($usuarioLogado)){    				    	    					
    				return $this->redirect()->toRoute('home');
    			}else{
    				return $this->redirect()->toRoute('login', array(
    						'action' => 'forget',
    						'erro'=>'1'
    				));
    			}
    		
    		}
    	} 	
	}
	
			
	public function sairAction()
	{	
		$this->destruirSessao();
				
        return $this->redirect()->toRoute('login');
	}
    
    public function destruirSessao()
    {
    	$sessionTimer = new Container('usuario_dados');
    	$sessionTimer->getManager()->getStorage()->clear('usuario_dados');
    	$sessionTimer->getManager()->destroy();   
    }
    /*
    function enviaEmail($msg, $assunto, $destinatario) {
    	$options = new SmtpOptions( array(
		"name" => "gmail",
		"host" => "smtp.gmail.com",
		"port" => 587,
		"connection_class" => "plain",
		"connection_config" => array( "username" => "",
		"password" => "","ssl" => "tls" )
		) );
		$mail = new Mail\Message();
		$mail->setBody($msg);
		$mail->setFrom('', '');
		$mail->addTo($destinatario);
		//$mail->addCC( 'ao@gmail.com' );
		$mail->setSubject($assunto);
		$transport = new SmtpTransport();
		$transport->setOptions( $options );
		$transport->send($mail);
    }
    */
}
















