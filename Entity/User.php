<?php

namespace Frne\Bundle\Neo4jUserBundle\Entity;

use HireVoice\Neo4j\Annotation as OGM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * @OGM\Entity(labels="User")
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @var string
     * @OGM\Auto
     */
    protected $id;

    /**
     * @var string
     * @OGM\Property
     * @OGM\Index
     */
    protected $username;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     * @OGM\Property
     */
    protected $password;

    /**
     * @var string
     * @OGM\Property
     */
    protected $salt;

    /**
     * @var array
     * @OGM\Property
     */
    protected $roles;

    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $salt
     * @param array|null $roles
     */
    public function __construct($username = null, $password = null, $salt = null, array $roles = array('ROLE_USER'))
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return UserInterface|User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return UserInterface|User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return UserInterface|User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return UserInterface|User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return array|\Symfony\Component\Security\Core\Role\Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return UserInterface|User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}