<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\UsuarioPanel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:crear-usuario-panel')]
class CrearUsuarioPanelCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $role_user = new Role();
        $role_user->setNombre('USER');
        $role_user->setSlug('ROLE_USER');
        $this->em->persist($role_user);
        $role_admin = new Role();
        $role_admin->setNombre('ADMIN');
        $role_admin->setSlug('ROLE_ADMIN');
        $this->em->persist($role_admin);
        $this->em->flush();
        $usuario = new UsuarioPanel();
        $usuario->setEmail('admin@sitio.ar');
        $usuario->addRole($role_user);
        $usuario->addRole($role_admin);
        $usuario->setPassword($this->hasher->hashPassword($usuario, '123456'));

        $this->em->persist($usuario);
        $this->em->flush();

        $output->writeln('UsuarioPanel creado con éxito!');
        return Command::SUCCESS;
    }
}
