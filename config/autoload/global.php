<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    // ...
	"db" => array(
		'driver' 		 => 'Pdo',
		'dsn' 			 => 'mysql:dbname=dbpage;host=localhost',
		'driver_options' => array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
		),
		
		'username' => 'root',
		'password' => '',
		

	),
	/* 'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
		'navigation' => array(
			'default' => array(
			array(
				'label' => 'Home',
				'route' => 'home',
			),
			array(
				'label' => 'Pages',
				'route' => 'page',
				'pages' => array(
					array(
					'label' => 'Добавить',
					'route' => 'page/add'
					)
					) 
			),
			array(
				'label' => 'Contact',
				'route' => 'page/logout',
			),
			array(
				'label' => 'Logout',
				'route' => 'page/logout',
			)
			)
		),
     ),*/
	
);
