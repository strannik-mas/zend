<?php


return array(

	'router' => array(
        'routes' => array(
            'page' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/page[/:action][/:id]',
					'constraints' => array(
						'action' => "[a-zA-Z][a-zA-Z0-9_-]*",
						'id' => "[0-9]+",
					),
                    'defaults' => array(
                        'controller' => 'Page\Controller\Page',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            //'Page\Controller\Index' => Controller\IndexController::class
			'Page\Controller\Page' => 'Page\Controller\PageController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
		'template_map' => array(
            'my_pagination_control' => __DIR__ . '/../../Page/view/page/page/my_pagination.phtml',
        ),
    ),
	/* 'acl' => array(
		'guest' => array(
			'home',       
			'page',       
		),
		'user' => array(
			'home',       
			'page',       
		),
		'admin' => array(
			'home',       
			'page',       
			'page/add',       
		),
	), */
);
