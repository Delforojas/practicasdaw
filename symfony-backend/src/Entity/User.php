<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Enum\RoleEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $full_name = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 150 )]
    private ?string $username = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', enumType: RoleEnum::class)]
    private RoleEnum $role = RoleEnum::USER;
    
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImage = null;

    public function getId(): ?int { return $this->id; }

    public function getFullName(): ?string { return $this->full_name; }
    public function setFullName(string $full_name): static { $this->full_name = $full_name; return $this; }

    public function getSurName(): ?string { return $this->surname; }
    public function setSurName(string $surname): static { $this->surname = $surname; return $this; }

    public function getUserName(): ?string { return $this->username; }
    public function setUserName(string $username): static { $this->username = $username; return $this; }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $address): self { $this->address = $address; return $this; }

    public function getPostalCode(): ?string { return $this->postal_code; }
    public function setPostalCode(?string $postalCode): self { $this->postal_code = $postalCode; return $this; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $city): self { $this->city = $city; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

        
    public function getRole(): RoleEnum{return $this->role;                  }

public function setRole(RoleEnum $role): static{$this->role = $role;                 return $this;}

public function getRoles(): array{return [$this->role->value];}

public function getUserIdentifier(): string{return (string) $this->email;}

public function getCreatedAt(): ?\DateTimeImmutable{return $this->createdAt; }

public function setCreatedAt(\DateTimeImmutable $dt): self 
        { 
            $this->createdAt = $dt; return $this; 
        }
public function getPhone(): ?string
        {
            return $this->phone;
        }


public function setPhone(?string $phone): self
        {
            $this->phone = $phone;
            return $this;
        }


public function eraseCredentials(): void {}

public function getProfileImage(): ?string
{
    return $this->profileImage;
}

public function setProfileImage(?string $profileImage): self
{
    $this->profileImage = $profileImage;
    return $this;
}
    
    }

