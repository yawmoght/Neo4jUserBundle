<?php

namespace Frne\Bundle\Neo4jUserBundle\Model;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class Neo4jUserManagerTest extends WebTestCase
{
    /**
     * @var Neo4jUserManager
     */
    private $userManager;

    public function setUp()
    {
        $this->userManager = $this->getContainer()->get('neo4j.user_manager');
    }

    public function testCreateUser()
    {
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $this->userManager->createUser());
    }

    public function testUpdatePassword()
    {
        $user = $this->userManager->createUser();

        $user
            ->setPlainPassword('1234')
            ->setSalt('sodfgsodf78gsdo8f7gsdo');

        $this->assertEquals(null, $user->getPassword());

        $this->userManager->updatePassword($user);

        $this->assertEquals(
            'SFnq38rwcU+xSD8vIHHig7pfzOG6hI+zntV6Qe/ughA0OTlmo7GLoBUwMIWfVytfzAIPJVgBZXupEueKqDjOCg==',
            $user->getPassword()
        );
    }
}
 