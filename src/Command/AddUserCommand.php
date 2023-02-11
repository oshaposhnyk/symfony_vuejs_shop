<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\User;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:add-user',
    description: 'Create user',
)]
class AddUserCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private AdminRepository $adminRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, AdminRepository $adminRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create a user...')
            ->addOption('email', 'em', InputOption::VALUE_REQUIRED, 'Email')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password')
            ->addOption('isAdmin', null, InputOption::VALUE_OPTIONAL, 'If set the user is created as an admin', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $isAdmin = $input->getOption('isAdmin');

        $io->title('Add User Command Wizard');
        $io->text([
            'Please enter some information',
        ]);

        if (!$email) {
            $email = $io->ask('Email');
        }

        if (!$password) {
            $password = $io->askHidden('Password');
        }

        if (!$isAdmin) {
            $isAdmin = $io->askQuestion(new Question('Is admin? (1 or 0)', 0));
        }
        $isAdmin = boolval($isAdmin);

        try {
            $user = $this->createUser($email, $password, $isAdmin);
        } catch (\RuntimeException $exception) {
            $io->comment($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success(printf('%s was successfully created: %s.', $isAdmin ? 'Admin' : 'User', $email));
        $stopwatch->stop('add-user-command');
        $stopwatchMessage = printf('New user\'s id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f mb',
            $user->getId(), $stopwatch->getEvent('add-user-command')->getDuration(), $stopwatch->getEvent('add-user-command')->getMemory() / 1000 / 1000);
        $io
            ->comment($stopwatchMessage);

        return Command::SUCCESS;
    }

    private function createUser(string $email, string $password, bool $isAdmin): User|Admin
    {
        if (!$isAdmin) {
            $existUser = $this->userRepository->findOneBy(criteria: ['email' => $email]);

            if ($existUser) {
                throw new \RuntimeException('User already exist');
            }

            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setIsVerified(true);
            $user->setIsDeleted(false);
        } else {
            $existUser = $this->adminRepository->findOneBy(criteria: ['email' => $email]);

            if ($existUser) {
                throw new \RuntimeException('User already exist');
            }

            $user = new Admin();
            $user->setEmail($email);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
