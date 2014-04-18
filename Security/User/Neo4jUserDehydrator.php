<?php

namespace Frne\Bundle\Neo4jUserBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class Neo4jUserDehydrator implements Dehydrator
{
    /**
     * @param UserInterface $user
     * @param string $targetClassName
     * @return UserInterface|void
     */
    function dehydrate(UserInterface $user, $targetClassName)
    {
        $user = new $targetClassName(
            $user->getUsername(),
            $user->getPassword(),
            $user->getSalt(),
            $user->getRoles()
        );

        return $user;
    }
} 