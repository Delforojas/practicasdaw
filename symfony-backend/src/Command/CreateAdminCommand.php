<?php 
namespace App\Command; 
use App\Entity\User; 
use App\Entity\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Console\Attribute\AsCommand; 
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface; 
use Symfony\Component\Console\Output\OutputInterface; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 
#[AsCommand(name: 'app:create-admin', description: 'Crea un admin si no existe')] class CreateAdminCommand extends Command 
{ 
 public function __construct( 
 private EntityManagerInterface $em, 
 private UserPasswordHasherInterface $passwordHasher 
 ) { 
 parent::__construct();
 } 
 protected function execute(InputInterface $input, OutputInterface $output): int  { 

 $now = new \DateTimeImmutable();
 $email = 'testsannu@gmail.com';
 $username = 'MartaSannu';
 $password = 'Admin123,';

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
 $user->setPhone('600123456');                
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
