<?php

namespace Frne\Bundle\Neo4jUserBundle\Command;

use Frne\Bundle\Neo4jUserBundle\Model\UserManager;
use HireVoice\Neo4j\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Creepy workaround for testing the command
     *
     * @param UserManager $userManager
     * @param EntityManager $em
     */
    public function setDeps(UserManager $userManager, EntityManager $em)
    {
        $this->userManager = $userManager;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('neo4j:user-bundle:create-user')
            ->setDescription('Create a user')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'The username to be set')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'The password of the user')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'The e-mail address of the user')
            ->addOption(
                'roles',
                'r',
                InputOption::VALUE_REQUIRED,
                'Roles to assign to the created user (comma separated)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$username = $input->getOption('username')) {
            throw new \InvalidArgumentException('Username must be set!');
        }

        if (!$password = $input->getOption('password')) {
            throw new \InvalidArgumentException('Password must be set!');
        }

        $userManager = ($this->userManager instanceof UserManager) ?
            $this->userManager :
            $this->getContainer()->get('neo4j.user_manager');

        $em = ($this->em instanceof EntityManager) ?
            $this->em :
            $this->getContainer()->get('neo4j.manager');

        $roles = (is_string($roles = $input->getOption('roles'))) ?
            explode(',', $roles) :
            array('ROLE_USER');

        $user = $userManager->createUser();

        $user
            ->setUsername($username)
            ->setPlainPassword($password = $input->getOption('password'))
            ->setSalt($userManager->generateSalt())
            ->setRoles($roles);

        if (is_string($email = $input->getOption('email'))) {
            $user->setEmail($email);
        }

        $userManager->updatePassword($user);

        $em->persist($user);
        $em->flush();

        $output->writeln(
            sprintf(
                "Created user '%s' with password '%s' and roles '%s'",
                $username,
                $password,
                implode(',', $roles)
            )
        );
    }
}