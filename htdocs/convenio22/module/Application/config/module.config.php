<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
	'router' => array(
		'routes' => array(

			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /application/:controller/:action

			'application' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/application',
					'defaults' => array(
						'__NAMESPACE__' => 'Application\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
					),
				),
			),

			'home' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/[:msg]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'index',
					),
				),
			),





			'login' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/login[/:action][/:erro]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Login',
						'action'     => 'index',
					),
				),
			),

			'trocar-usuario' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/trocar-usuario/:id',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Login',
						'action'     => 'trocar-usuario',
					),
					'constraints' => array(
						'id' => '\d+'
					)
				),
			),



			'ferias' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/ferias',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Ferias',
						'action'     => 'index',
					),
					'constraints' => array(
						//   'id' => '\d+'
					)
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Ferias',
								'action'     => 'add'
							),
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Ferias',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),





			'setor' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/setor',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Setor',
						'action'     => 'index',
					),
					'constraints' => array(
						//   'id' => '\d+'
					)
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Setor',
								'action'     => 'add'
							),
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Setor',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Setor',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),




			'acesso-negado' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/acesso-negado',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'acessonegado',
					),
				),
			),


			'area' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/area',
					'defaults' => array(
						'controller' => 'Application\Controller\Area',
						'action'     => 'index',
					)
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'add'
							),
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'funcao' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/funcao/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'funcao'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'funcao-add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/funcao-add/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'funcao-add'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'funcao-delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/funcao-delete/:id/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Area',
								'action'     => 'funcao-delete'
							),
							'constraints' => array(
								'id' => '\d+',
								'area_id' => '\d+',
							)
						)
					),
				)
			),

			'relatorio-colaborador' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/relatorio-colaborador',
					'defaults' => array(
						'controller' => 'Application\Controller\RelatorioColaborador',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'funcoes' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/funcoes',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'funcoes'
							),
							'constraints' => array(
								// 'usuario_id' => '\d+'
							)
						)
					),
					'bonificacoes' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/bonificacoes',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'bonificacoes'
							),
							'constraints' => array(
								// 'usuario_id' => '\d+'
							)
						)
					),
					'bolsista' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/bolsista',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'bolsista'
							),
							'constraints' => array(
								// 'usuario_id' => '\d+'
							)
						)
					),
					'ver-relatorio-colaborador' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/ver-relatorio-colaborador/:usuario_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'ver-relatorio-colaborador'
							),
							'constraints' => array(
								'usuario_id' => '\d+'
							)
						)
					),
					'ver-relatorio-colaborador-projeto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/ver-relatorio-colaborador-projeto/:id/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'ver-relatorio-colaborador-projeto'
							),
							'constraints' => array(
								'id' => '\d+',
								'projeto_id' => '\d+'
							)
						)
					),
					'ver-relatorio-estagiarios' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/ver-relatorio-estagiarios',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'ver-relatorio-estagiarios'
							),
						)
					),
					'get-relatorio-servicos' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-servicos/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'get-relatorio-servicos'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-relatorio-estagio' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-estagio/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'get-relatorio-estagio'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-relatorio-mensal-atividade' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-mensal-atividade/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'get-relatorio-mensal-atividade'
							),
							'constraints' => array(
								'id' => '\d+',
								//'usuario_id' => '\d+'
							)
						)
					),

					'assinar-relatorio-mensal-atividade' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/assinar-relatorio-mensal-atividade/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'assinar-relatorio-mensal-atividade'
							),
							'constraints' => array(
								'id' => '\d+',
								//'usuario_id' => '\d+'
							)
						)
					),
					'get-relatorio-matriz-responsabilidade' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-matriz-responsabilidade/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'get-relatorio-matriz-responsabilidade'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

					'get-relatorio-especifico' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-especifico/:id/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'get-relatorio-especifico'
							),
							'constraints' => array(
								'id' => '\d+',
								'projeto_id' => '\d+',
							)
						)
					),

					'atividade-mensal' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/atividade-mensal',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'atividade-mensal'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

					'exportar-atividade-mensal' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar-atividade-mensal/:mes/:ano',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioColaborador',
								'action'     => 'exportar-atividade-mensal'
							),
							'constraints' => array(
								'mes' => '\d+',
								'ano' => '\d+'
							)
						)
					),


				),
			),



			'relatorio-cumprimento-objeto' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/relatorio-cumprimento-objeto',
					'defaults' => array(
						'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
								'action'     => 'consulta'
							),
							'constraints' => array(
								//'id' => '\d+'
							)
						)
					),
					'get-relatorio-cumprimento-objeto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-cumprimento-objeto/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
								'action'     => 'get-relatorio-cumprimento-objeto'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),

					'get-relatorio-cumprimento-objeto-word' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-relatorio-cumprimento-objeto-word/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
								'action'     => 'get-relatorio-cumprimento-objeto-word'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),
					
					'get-submetas' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-submetas/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
								'action'     => 'get-submetas'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'exportar-relatorio-cumprimento-objeto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar-relatorio-cumprimento-objeto/:area_id/:submeta_id',
							'defaults' => array(
								'controller' => 'Application\Controller\RelatorioCumprimentoObjeto',
								'action'     => 'exportar-relatorio-cumprimento-objeto'
							),
							'constraints' => array(
								'area_id' => '\d+',
								'submeta_id' => '\d+'
							)
						)
					),
				),
			),

			'atividade-ptc' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/atividade-ptc',
					'defaults' => array(
						'controller' => 'Application\Controller\AtividadePTC',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'add'
							),
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'concluido' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/concluido',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'concluido'
							),
						)
					),
					'get-submetas' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-submetas/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'get-submetas'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'exportar' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadePTC',
								'action'     => 'exportar'
							),
							'constraints' => array(
								//	'id' => '\d+'
							)
						)
					),
				)
			),

			'usuario-atividade' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/usuario-atividade',
					'defaults' => array(
						'controller' => 'Application\Controller\UsuarioAtividade',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioAtividade',
								'action'     => 'add'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						)
					),
					'add-especifico' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add-especifico/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioAtividade',
								'action'     => 'addEspecifico'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioAtividade',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

					'edit-especifico' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit-especifico/:id/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioAtividade',
								'action'     => 'edit-especifico'
							),
							'constraints' => array(
								'id' => '\d+',
								'projeto_id' => '\d+'
							)
						)
					),

					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioAtividade',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						)
					),
				)
			),

			'usuario-projeto' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/usuario-projeto',
					'defaults' => array(
						'controller' => 'Application\Controller\UsuarioProjeto',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:usuario_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioProjeto',
								'action'     => 'add'
							),
							'constraints' => array(
								'usuario_id' => '\d+'
							)
						)
					),
					'set-representante' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/set-representante/:id/:usuario_projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioProjeto',
								'action'     => 'set-representante'
							),
							'constraints' => array(
								'id' => '\d+',
								'usuario_projeto_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioProjeto',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-projetos' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-projetos/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioProjeto',
								'action'     => 'get-projetos'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:usuario_id',
							'defaults' => array(
								'controller' => 'Application\Controller\UsuarioProjeto',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'usuario_id' => '\d+'
							)
						)
					),
				)
			),

			'colaborador' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/colaborador',
					'defaults' => array(
						'controller' => 'Application\Controller\Colaborador',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'add'
							),
							'constraints' => array()
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'upload-documento' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/upload-documento/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'upload-documento'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

					'delete-documento' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete-documento/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'delete-documento'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'exportar' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'exportar'
							),
							'constraints' => array()
						)
					),
					'exportar1' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar1',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'exportar1'
							),
							'constraints' => array()
						)
					),
					'exportar2' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar2',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'exportar2'
							),
							'constraints' => array()
						)
					),
					'get-projetos' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-projetos/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'get-projetos'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-folha-ponto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-folha-ponto/:usuario_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'get-folha-ponto'
							),
							'constraints' => array(
								'usuario_id' => '\d+',
							)
						)
					),
					'folha-ponto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/folha-ponto',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'folha-ponto'
							),
							'constraints' => array()
						)
					),
					'uniselva' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/uniselva',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'uniselva'
							),
							'constraints' => array()
						)
					),
					'uniselva-view' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/uniselva-view/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'uniselva-view'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-funcoes' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-funcoes/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Colaborador',
								'action'     => 'get-funcoes'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),


			'participante' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/participante',
					'defaults' => array(
						'controller' => 'Application\Controller\Participante',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'add'
							)

						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'associar-participante' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/associar-participante/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'associar-participante'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete-projeto-participante' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete-projeto-participante/:usuario_id/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'delete-projeto-participante'
							),
							'constraints' => array(
								'usuario_id' => '\d+',
								'id' => '\d+'

							)
						)
					),
					'exportar' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar',
							'defaults' => array(
								'controller' => 'Application\Controller\Participante',
								'action'     => 'exportar'
							),
							'constraints' => array()
						)
					),

				)
			),

			'atividade-projeto' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/atividade-projeto',
					'defaults' => array(
						'controller' => 'Application\Controller\AtividadeProjeto',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'add'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						),
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:projeto_id/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'get-atividades-projeto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/get-atividades-projeto/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'get-atividades-projeto'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

					'exportar' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar/:projeto_id',
							'defaults' => array(
								'controller' => 'Application\Controller\AtividadeProjeto',
								'action'     => 'exportar'
							),
							'constraints' => array(
								'projeto_id' => '\d+'
							)
						)
					),
				)
			),

			'cumprimento-objeto-atividade' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/cumprimento-objeto-atividade',
					'defaults' => array(
						'controller' => 'Application\Controller\CumprimentoObjetoAtividade',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\CumprimentoObjetoAtividade',
								'action'     => 'add'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\CumprimentoObjetoAtividade',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),
			'artefato' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/artefato',
					'defaults' => array(
						'controller' => 'Application\Controller\Artefato',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Artefato',
								'action'     => 'add'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:atividade_id/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Artefato',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+',
								'atividade_id' => '\d+'
							)
						)
					),
				)
			),

			'cumprimento-objeto' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/cumprimento-objeto',
					'defaults' => array(
						'controller' => 'Application\Controller\CumprimentoObjeto',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\CumprimentoObjeto',
								'action'     => 'add'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),

					'add1' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add1/:atividade_id',
							'defaults' => array(
								'controller' => 'Application\Controller\CumprimentoObjeto',
								'action'     => 'add1'
							),
							'constraints' => array(
								'atividade_id' => '\d+'
							)
						)
					),

					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:atividade_id/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\CumprimentoObjeto',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+',
								'atividade_id' => '\d+'
							)
						)
					),
				)
			),

			'submeta' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/submeta',
					'defaults' => array(
						'controller' => 'Application\Controller\Submeta',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Submeta',
								'action'     => 'add'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:area_id/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Submeta',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Submeta',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:area_id/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Submeta',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),

			'nucleo' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/nucleo',
					'defaults' => array(
						'controller' => 'Application\Controller\Nucleo',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Nucleo',
								'action'     => 'add'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:area_id/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Nucleo',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Nucleo',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/:area_id/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Nucleo',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),
			'funcao' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/funcao',
					'defaults' => array(
						'controller' => 'Application\Controller\Funcao',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Funcao',
								'action'     => 'add'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Funcao',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Funcao',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Funcao',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),

			'bonificacao' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/bonificacao',
					'defaults' => array(
						'controller' => 'Application\Controller\Bonificacao',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Bonificacao',
								'action'     => 'add'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bonificacao',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bonificacao',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bonificacao',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),


			'bolsa' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/bolsa',
					'defaults' => array(
						'controller' => 'Application\Controller\Bolsa',
						'action'     => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Bolsa',
								'action'     => 'add'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bolsa',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'consulta' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/consulta/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bolsa',
								'action'     => 'consulta'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Bolsa',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),


			'representante' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/representante',
					'defaults' => array(
						'controller' => 'Application\Controller\Representante',
						'action'     => 'index',
					)
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Representante',
								'action'     => 'add'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Representante',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Representante',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
				)
			),


			'projeto' => array(
				'type' => 'literal',
				'options' => array(
					'route'    => '/projeto',
					'defaults' => array(
						'controller' => 'Application\Controller\Projeto',
						'action'     => 'index',
					)
				),
				'may_terminate' => true,
				'child_routes'  => array(
					'add' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/add',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'add'
							)
						)
					),
					'listacidade' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/listacidade/:area_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'listacidade'
							),
							'constraints' => array(
								'area_id' => '\d+'
							)
						)
					),
					'inativos' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/inativos',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'inativos'
							)
						)
					),
					'meus-projetos' => array(
						'type' => 'literal',
						'options' => array(
							'route'    => '/meus-projetos',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'meus-projetos'
							)
						)
					),
					'edit' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/edit/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'edit'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'delete' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/delete/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'delete'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'ver-colaboradores' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/ver-colaboradores/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'verColaboradores'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'ver-participantes' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/ver-participantes/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'verParticipantes'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'cadastrar-participante' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/cadastrar-participante/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'consultar-participante'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),
					'associar-atividades-projeto-colaborador' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/associar-atividades-projeto-colaborador/:projeto_id/:usuario_id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'associarAtividadesProjetoColaborador'
							),
							'constraints' => array(
								'projeto_id' => '\d+',
								'usuario_id' => '\d+'
							)
						)
					),
					'exportar-colaboradores-por-projeto' => array(
						'type' => 'segment',
						'options' => array(
							'route'    => '/exportar-colaboradores-por-projeto/:id',
							'defaults' => array(
								'controller' => 'Application\Controller\Projeto',
								'action'     => 'exportarColaboradoresPorProjeto'
							),
							'constraints' => array(
								'id' => '\d+'
							)
						)
					),

				)
			),

			'usuario' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/usuario[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\Usuario',
						'action'     => 'index',
					),
				),
			),

			'perfil-acesso' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/perfil-acesso[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults' => array(
						'controller' => 'Application\Controller\PerfilAcesso',
						'action'     => 'index',
					),
				),
			),

			

		),
	),
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
		),
		'factories' => array(
			'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
		),
	),
	'translator' => array(
		'locale' => 'en_US',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => Controller\IndexController::class,
			'Application\Controller\Login'    => Controller\LoginController::class,
			'Application\Controller\Area'    => Controller\AreaController::class,
			'Application\Controller\Submeta'    => Controller\SubmetaController::class,
			'Application\Controller\Nucleo'    => Controller\NucleoController::class,
			'Application\Controller\Representante'    => Controller\RepresentanteController::class,
			'Application\Controller\Projeto'    => Controller\ProjetoController::class,
			'Application\Controller\Usuario'    => Controller\UsuarioController::class,
			'Application\Controller\Participante'    => Controller\ParticipanteController::class,
			'Application\Controller\PerfilAcesso'    => Controller\PerfilAcessoController::class,
			'Application\Controller\AtividadeProjeto'    => Controller\AtividadeProjetoController::class,
			'Application\Controller\AtividadeProjetoUsuario'    => Controller\AtividadeProjetoUsuarioController::class,
			'Application\Controller\AtividadePTC'    => Controller\AtividadePTCController::class,
			'Application\Controller\CumprimentoObjeto'    => Controller\CumprimentoObjetoController::class,
			'Application\Controller\CumprimentoObjetoAtividade'    => Controller\CumprimentoObjetoAtividadeController::class,
			'Application\Controller\Artefato'    => Controller\ArtefatoController::class,
			'Application\Controller\RelatorioColaborador'    => Controller\RelatorioColaboradorController::class,
			'Application\Controller\RelatorioCumprimentoObjeto'    => Controller\RelatorioCumprimentoObjetoController::class,
			'Application\Controller\UsuarioAtividade'    => Controller\UsuarioAtividadeController::class,
			'Application\Controller\Colaborador'    => Controller\ColaboradorController::class,
			'Application\Controller\UsuarioProjeto'    => Controller\UsuarioProjetoController::class,
			'Application\Controller\Ferias'    => Controller\FeriasController::class,
			'Application\Controller\Setor'    => Controller\SetorController::class,
			'Application\Controller\Funcao'    => Controller\FuncaoController::class,
			'Application\Controller\Bonificacao'    => Controller\BonificacaoController::class,
			'Application\Controller\Bolsa'    => Controller\BolsaController::class,
		),
	),
	'module_layouts' => array(
		'Application' => 'layout/application.phtml',
	),
	'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
		'doctype'                  => 'HTML5',
		'not_found_template'       => 'error/404',
		'exception_template'       => 'error/index',
		'template_map' => array(
			'layout/layout'           => __DIR__ . '/../view/layout/application.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'error/404'               => __DIR__ . '/../view/error/404.phtml',
			'error/index'             => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	// Placeholder for console routes
	'console' => array(
		'router' => array(
			'routes' => array(),
		),
	),
);
