<?php

namespace Frne\Bundle\Neo4jUserBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

interface Dehydrator
{
    /**
     * @param UserInterface $user
     * @param string $targetClassName
     * @return UserInterface
     */
    function dehydrate(UserInterface $user, $targetClassName);
} 