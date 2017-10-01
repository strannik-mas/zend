<?php


return array(

	'router' => array(
        'routes' => array(
            'module2' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/module2[/:action][/:id]',
					'constraints' => array(
						'action' => "[a-zA-Z][a-zA-Z0-9_-]*",
						'id' => "[0-9]+",
					),
                    'defaults' => array(
                        'controller' => 'Module2\Controller\Module2',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            //'Module2\Controller\Index' => Controller\IndexController::class
			'Module2\Controller\Module2' => 'Module2\Controller\Module2Controller'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
