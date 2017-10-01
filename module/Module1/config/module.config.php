<?php


return array(

	'router' => array(
        'routes' => array(
            'module1' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/module1[/:action][/:id]',
					'constraints' => array(
						'action' => "[a-zA-Z][a-zA-Z0-9_-]*",
						'id' => "[0-9]+",
					),
                    'defaults' => array(
                        'controller' => 'Module1\Controller\Module1',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            //'Module1\Controller\Index' => Controller\IndexController::class
			'Module1\Controller\Module1' => 'Module1\Controller\Module1Controller'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
