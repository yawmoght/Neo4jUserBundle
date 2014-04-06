<?php

namespace Frne\Bundle\Neo4jUserBundle\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    /**
     * @var \HireVoice\Neo4j\EntityManager
     */
    private $em;

    /**
     * @var \HireVoice\Neo4j\Repository
     */
    private $repo;

    public function setUp()
    {
        $this->em = $this->getContainer()->get('neo4j.manager');
        $this->repo = $this->getContainer()->get('neo4j.repository.user');
    }

    public function testImplementsUserInterface()
    {
        $user = new User('johndoe', '1234', 'sfo7sdgfos9d7gfos9dfg', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $user);
    }

    public function testCanBeStoredInNeo4jDatabase()
    {
        $user = new User('johndoe', '1234', 'sfo7sdgfos9d7gfos9dfg', ['ROLE_USER', 'ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush(); // Stores both Jane and John, along with the new relation

        $fetchedUser = $this->repo->findOneBy(array('username' => 'johndoe'));

        $this->assertInstanceOf('Frne\Bundle\Neo4jUserBundle\Entity\User', $fetchedUser);
        $this->assertEquals('johndoe', $fetchedUser->getUsername());
        $this->assertEquals('sfo7sdgfos9d7gfos9dfg', $fetchedUser->getSalt());
        $this->assertEquals('1234', $fetchedUser->getPassword());
        $this->assertInternalType('array', $fetchedUser->getRoles());
        $this->assertTrue(in_array('ROLE_USER', $fetchedUser->getRoles()));
    }

    public function tearDown()
    {
        $users = $this->repo->findBy(array('username' => 'johndoe'));

        foreach ($users as $user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }
}
 