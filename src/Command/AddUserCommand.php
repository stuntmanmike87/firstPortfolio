<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'add-user',
    description: 'Creates users and stores them in the database',
)]
final class AddUserCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Validator $validator,
        private readonly UserRepository $user
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
        ->setHelp($this->getCommandHelp())
        ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')// username
        ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
        ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
        ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        /** @var string $username */
        $username = $input->getArgument('username');

        /** @var string $plainPassword */
        $plainPassword = $input->getArgument('password');

        /** @var string $email */
        $email = $input->getArgument('email');

        /** @var bool $isAdmin */
        $isAdmin = $input->getOption('admin');

        // make sure to validate the user data is correct
        $this->validateUserData($username, $plainPassword, $email);

        // create the user and hash its password
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        // $user->setRoles([$isAdmin ? User::ROLE_ADMIN : User::ROLE_USER]);

        // See https://symfony.com/doc/5.4/security.html#registering-the-user-hashing-passwords
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isAdmin ? 'Administrator user' : 'User', $user->getUsername(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');

        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    private function validateUserData(string $username, string $plainPassword, string $email): void
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->user->findOneBy(['username' => $username]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" nickname.', $username));
        }

        // validate password and email if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validateUsername($username);

        // check if a user with the same email already exists.
        $existingEmail = $this->user->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> command creates new users and saves them in the database:

              <info>php %command.full_name%</info> <comment>nickname password email</comment>

            By default the command creates regular users. To create administrator users,
            add the <comment>--admin</comment> option:

              <info>php %command.full_name%</info> nickname password email <comment>--admin</comment>

            If you omit any of the three required arguments, the command will ask you to
            provide the missing values:

              # command will ask you for the email
              <info>php %command.full_name%</info> <comment>nickname password</comment>

              # command will ask you for the email and password
              <info>php %command.full_name%</info> <comment>nickname</comment>

              # command will ask you for all arguments
              <info>php %command.full_name%</info>
            HELP;
    }
}
