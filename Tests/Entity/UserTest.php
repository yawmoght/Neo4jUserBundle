<?php

namespace Frne\Bundle\Neo4jUserBundle\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testImplementsUserInterface()
    {
        $user = new User('johndoe', '1234', 'sfo7sdgfos9d7gfos9dfg', array('ROLE_USER', 'ROLE_ADMIN'));
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $user);
    }
}
 