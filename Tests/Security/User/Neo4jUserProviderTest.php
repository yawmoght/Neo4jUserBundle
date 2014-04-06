<?php

namespace Frne\Bundle\Neo4jUserBundle\Security\User;

use Frne\Bundle\Neo4jUserBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class Neo4jUserProviderTest extends WebTestCase
{
    /**
     * @var \HireVoice\Neo4j\EntityManager
     */
    private $em;

    /**
     * @var \HireVoice\Neo4j\Repository
     */
    private $repo;

    /**
     * @var Neo4jUserProvider
     */
    private $userProvider;

    public function setUp()
    {
        $this->em = $this->getContainer()->get('neo4j.manager');
        $this->repo = $this->getContainer()->get('neo4j.repository.user');
        $this->userProvider = $this->getContainer()->get('neo4j_user_provider');
    }

    public function testLoadUserByUsername()
    {
        $user = new User('johndoe', '1234', 'sfo7sdgfos9d7gfos9dfg', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();

        $loadedUser = $this->userProvider->loadUserByUsername('johndoe');

        $this->assertInstanceOf('Frne\Bundle\Neo4jUserBundle\Entity\User', $loadedUser);
    }

    public function testThrowsExceptionWhileTryingToLoadUnknownUser()
    {
        $this->setExpectedException('Symfony\Component\Security\Core\Exception\UsernameNotFoundException');
        $this->userProvider->loadUserByUsername('johndoe');
    }

    public function testSupportsClassUser()
    {
        $this->assertTrue($this->userProvider->supportsClass('Frne\Bundle\Neo4jUserBundle\Entity\User'));
        $this->assertFalse($this->userProvider->supportsClass('Symfony\Component\Security\Core\User\User'));
    }

    public function testReloadUser() {
        $user = new User('johndoe', '1234', 'sfo7sdgfos9d7gfos9dfg', ['ROLE_USER', 'ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();

        $loadedUser = $this->userProvider->loadUserByUsername('johndoe');
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $loadedUser->getRoles());

        $user->setRoles(['NO_ROLE']);
        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();

        $refreshedUser = $this->userProvider->refreshUser($loadedUser);
        $this->assertEquals(['NO_ROLE'], $refreshedUser->getRoles());
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
 