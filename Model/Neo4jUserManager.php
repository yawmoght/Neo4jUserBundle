<?php

namespace Frne\Bundle\Neo4jUserBundle\Model;

use Frne\Bundle\Neo4jUserBundle\Entity\User;
use HireVoice\Neo4j\Repository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Neo4jUserManager extends Repository implements UserManager
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var string
     */
    private $userEntityClass;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     * @param \HireVoice\Neo4j\Meta\Entity $userEntityClass
     */
    function __construct(EncoderFactoryInterface $encoderFactory, $userEntityClass)
    {
        $this->encoderFactory = $encoderFactory;
        $this->userEntityClass = $userEntityClass;
    }

    /**
     * Creates an empty user
     *
     * @return UserInterface|User
     */
    public function createUser()
    {
        return new $this->userEntityClass;
    }

    /**
     * Updates and encodes users password
     *
     * @param UserInterface|User $user
     * @return UserInterface
     */
    public function updatePassword(UserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * Generates a unique password salt
     *
     * @return string
     */
    function generateSalt()
    {
        return base64_encode(uniqid('', true) . sha1(time()));
    }

    /**
     * @param UserInterface $user
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    private function getEncoder(UserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

} 