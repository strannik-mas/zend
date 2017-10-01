<?php

namespace AclService;

use Zend\EventManager\EventManager\AwareInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\EventManager\EventManagerAwareTrait;

class Acl implements EventManagerAwareInterface {

    use EventManagerAwareTrait;

    const USER_GUEST = 'guest';

    protected $acl;

    public function __construct(Acl $acl) {
        $this->setAcl($acl);
    }

    public function setAcl(Acl $acl) {
        $this->acl = $acl;
        return $this;
    }

    public function getAcl() {
        return $this->acl;
    }

    public function setup(array $config) {
        $acl = $this->getAcl();
        foreach ($config as $role => $resources) {
            if (!$acl->hasRole($role)) {
                $acl->addRole(new Role($role));
            }
            foreach ($resources as $resource) {
                if (!$acl->hasResource($resource)) {
                    $acl->addResource(new Resource($resource));
                }
                $acl->deny($role, $resource);
            }
        }
    }

    public function isAllowed($role, $resource) {
        return (!($this->getAcl()->hasResource($resource) && $this->getAcl()->hasRole($role))) || $this->getAcl()->isAllowed($role, $resource);
    }

}