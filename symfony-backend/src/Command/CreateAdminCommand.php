<?php 
namespace App\Command; 
use App\Entity\User; 
use App\Entity\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Console\Attribute\AsCommand; 
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 
#[AsCommand(name: 'app:create-admin', description: 'Crea un admin si no existe')] class CreateAdminCommand extends Command 
{ 
 public function __construct( 
 private EntityManagerInterface $em, 
 private UserPasswordHasherInterface $passwordHasher 
 ) { 
 parent::__construct();
 } 
 protected function configure(): void
 {
  $this->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Email del administrador')
       ->addOption('username', null, InputOption::VALUE_OPTIONAL, 'Nombre de usuario del administrador');
 }
 protected function execute(InputInterface $input, OutputInterface $output): int  { 

  $now = new \DateTimeImmutable();
  $helper = $this->getHelper('question');
  $value = function (string $option, string $env, string $prompt, bool $hidden = false) use ($input, $output, $helper): string {
      $value = $input->getOption($option);
      if (!is_string($value) || $value === '') {
          $value = $_SERVER[$env] ?? $_ENV[$env] ?? getenv($env);
      }
      if ((!is_string($value) || $value === '') && $input->isInteractive()) {
          $question = new Question($prompt);
          $question->setHidden($hidden);
          $value = $helper->ask($input, $output, $question);
      }
      if (!is_string($value) || trim($value) === '') {
          throw new \InvalidArgumentException(sprintf('Missing administrator value: %s or %s.', $option, $env));
      }
      return $hidden ? $value : trim($value);
  };

  $email = $value('email', 'ADMIN_EMAIL', 'Administrator email: ');
  $username = $value('username', 'ADMIN_USERNAME', 'Administrator username: ');
  $password = $_SERVER['ADMIN_PASSWORD'] ?? $_ENV['ADMIN_PASSWORD'] ?? getenv('ADMIN_PASSWORD');
  if ((!is_string($password) || $password === '') && $input->isInteractive()) {
      $question = new Question('Administrator password: ');
      $question->setHidden(true);
      $password = $helper->ask($input, $output, $question);
  }
  if (!is_string($password) || trim($password) === '') {
      throw new \InvalidArgumentException('Missing administrator value: ADMIN_PASSWORD.');
  }

 $repo = $this->em->getRepository(User::class); 
 
        if ($repo->findOneBy(['email' => $email])) {
            $output->writeln('<comment>Ya existe un usuario con ese email.</comment>');
            return Command::SUCCESS;
        }

        if ($repo->findOneBy(['username' => $username])) {
            $output->writeln('<comment>Ya existe un usuario con ese username.</comment>');
            return Command::SUCCESS;
        }

 $user = new User(); 
 $user->setFullName('Administrador');                
 $user->setSurName('Sannu'); 
 $user->setUserName($username); 
 $user->setEmail($email); 
 $user->setRole(RoleEnum::ADMIN);
 $user->setPhone('600<REDACTED-JWT_PASSPHRASE>56');                
 $user->setAddress('Calle Admin 1');          
 $user->setPostalCode('28080');               
 $user->setCity('Madrid');          
 $user->setPassword( 
 $this->passwordHasher->hashPassword($user, $password));
 $user->setCreatedAt($now); 
 $this->em->persist($user); 
 $this->em->flush(); 
 $output->writeln('Admin creado.'); 
 return Command::SUCCESS; 
 } 
}
