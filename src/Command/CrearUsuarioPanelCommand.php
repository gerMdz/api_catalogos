<?php

namespace App\Command;

use App\Entity\Role;
use App\Entity\UsuarioPanel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
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
        $helper = $this->getHelper('question');

        // Preguntar email
        $emailQuestion = new Question('📧 Ingrese el email del nuevo usuario: ');
        $email = $helper->ask($input, $output, $emailQuestion);

        // Preguntar contraseña (oculta)
        $passwordQuestion = new Question('🔒 Ingrese la contraseña: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        // Confirmar contraseña
        $passwordConfirmQuestion = new Question('🔒 Confirme la contraseña: ');
        $passwordConfirmQuestion->setHidden(true);
        $passwordConfirmQuestion->setHiddenFallback(false);
        $passwordConfirm = $helper->ask($input, $output, $passwordConfirmQuestion);

        if ($password !== $passwordConfirm) {
            $output->writeln('<error>❌ Las contraseñas no coinciden.</error>');
            return Command::FAILURE;
        }

        // Crear roles si no existen
        $roleUser = $this->em->getRepository(Role::class)->findOneBy(['slug' => 'ROLE_USER']);
        if (!$roleUser) {
            $roleUser = new Role();
            $roleUser->setNombre('Usuario');
            $roleUser->setSlug('ROLE_USER');
            $this->em->persist($roleUser);
        }

        $roleAdmin = $this->em->getRepository(Role::class)->findOneBy(['slug' => 'ROLE_ADMIN']);
        if (!$roleAdmin) {
            $roleAdmin = new Role();
            $roleAdmin->setNombre('Administrador');
            $roleAdmin->setSlug('ROLE_ADMIN');
            $this->em->persist($roleAdmin);
        }

        $this->em->flush();

        // Crear el usuario
        $usuario = new UsuarioPanel();
        $usuario->setEmail($email);
        $usuario->addRole($roleUser);
        $usuario->addRole($roleAdmin);
        $usuario->setPassword($this->hasher->hashPassword($usuario, $password));

        $this->em->persist($usuario);
        $this->em->flush();

        $output->writeln('<info>✅ UsuarioPanel creado con éxito!</info>');
        return Command::SUCCESS;
    }
}
