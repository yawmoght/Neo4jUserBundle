<?php

namespace Frne\Bundle\Neo4jUserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('neo4j:user-bundle:create-user')
            ->setDescription('Create a user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getContainer()->get('neo4j.user_manager');
        $em = $this->getContainer()->get('neo4j.manager');
        $dialog = $this->getHelperSet()->get('dialog');

        $username = $dialog->ask(
            $output,
            'Username: '
        );

        $password = $dialog->ask(
            $output,
            'Password: '
        );

        $user = $userManager->createUser();

        $user
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setSalt($userManager->generateSalt());

        $userManager->updatePassword($user);

        $em->persist($user);
        $em->flush();

        $output->writeln("Created user '$username' with password '$password'");
    }
}