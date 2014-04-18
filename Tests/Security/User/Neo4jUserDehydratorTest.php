<?php

namespace Frne\Bundle\Neo4jUserBundle\Security\User;

use Frne\Bundle\Neo4jUserBundle\Entity\User;

class Neo4jUserDehydratorTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsDehydratorInterface() {
        $dehydrator = new Neo4jUserDehydrator();

        $this->assertInstanceOf('Frne\Bundle\Neo4jUserBundle\Security\User\Dehydrator', $dehydrator);
    }

    public function testDehydrate()
    {
        $dehydrator = new Neo4jUserDehydrator();
        $user = new User();
        $user
            ->setUsername('foo')
            ->setPlainPassword('1234')
            ->setEmail('foo@bar.tld');

        $this->assertEquals('foo@bar.tld', $user->getEmail());

        $user = $dehydrator->dehydrate($user, get_class($user));

        $this->assertEquals(null, $user->getEmail());
    }
}
 