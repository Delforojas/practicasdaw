<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $class_type = null;

    #[ORM\Column(length: 255)]
    private ?string $total_uses = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $valid_until = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getClassType(): ?string
    {
        return $this->class_type;
    }

    public function setClassType(string $class_type): static
    {
        $this->class_type = $class_type;

        return $this;
    }

    public function getTotalUses(): ?string
    {
        return $this->total_uses;
    }

    public function setTotalUses(string $total_uses): static
    {
        $this->total_uses = $total_uses;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->valid_until;
    }

    public function setValidUntil(?\DateTimeImmutable $valid_until): static
    {
        $this->valid_until = $valid_until;

        return $this;
    }

}


