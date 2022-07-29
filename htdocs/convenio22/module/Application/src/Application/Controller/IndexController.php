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

class IndexController extends AbstractActionController
{	    	
    public function indexAction()
    {
    	$dados_sessao_atual = new Container('usuario_dados');

    	if(!isset($dados_sessao_atual->id)){
    		return $this->redirect()->toRoute('login', array('action'=>'index'));
    	}
    	
    
    	return new ViewModel(array(
    	    
        ));
    }

    public function acessoNegadoAction()
    {
        $dados_sessao_atual = new Container('usuario_dados');

        if(!isset($dados_sessao_atual->id)){
            return $this->redirect()->toRoute('login', array('action'=>'index'));
        }


        return new ViewModel(array(

        ));
    }

}
