<?php

use Acl\Listener\Acl as AclListener;
use Acl\Service\Acl as AclService;
use Zend\Permissions\Acl\Acl;

return array(
    'service_manager' => array(
        'factories' => array(
            'AclListener' => function($sm) {
                return new AclListener($sm->get('AclService'), $sm->get('Zend\Authentication\AuthenticationService'));
            },
            'AclService' => function($sm) {
                $config = $sm->get('config');
                $service = new AclService(new Acl);
                if (!empty($config['acl'])) {
                    $service->setup($config['acl']);
                }       
                return $service;
            }   
        )
    ),
);
