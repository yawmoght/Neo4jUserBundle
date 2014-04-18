<?php

namespace Frne\Bundle\Neo4jUserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Frne\Bundle\Neo4jUserBundle\Entity\User;

interface UserManager
{
    /**
     * Creates an empty user
     *
     * @return UserInterface|User
     */
    function createUser();

    /**
     * Updates and encodes users password
     *
     * @param UserInterface $user
     * @return UserInterface
     */
    function updatePassword(UserInterface $user);

    /**
     * Generates a unique password salt
     *
     * @return string
     */
    function generateSalt();
}
