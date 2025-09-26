<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $class_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $wallet_voucher_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $created_by_admin_id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reservation_date = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?User $user = null;
    
    #[ORM\ManyToOne(targetEntity: ClassSession::class)]
    #[ORM\JoinColumn(name: "class_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?ClassSession $class = null;

    #[ORM\ManyToOne(targetEntity: WalletVoucher::class)]
    #[ORM\JoinColumn(name: "wallet_voucher_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?WalletVoucher $wallet_voucher = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'created_by_admin_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $created_by_admin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getClassId(): ?int
    {
        return $this->class_id;
    }

    public function setClassId(int $class_id): static
    {
        $this->class_id = $class_id;

        return $this;
    }

    public function getWalletVoucherId(): ?int
    {
        return $this->wallet_voucher_id;
    }

    public function setWalletVoucherId(?int $wallet_voucher_id): static
    {
        $this->wallet_voucher_id = $wallet_voucher_id;

        return $this;
    }

    public function getCreatedByAdminId(): ?int
    {
        return $this->created_by_admin_id;
    }

    public function setCreatedByAdminId(?int $created_by_admin_id): static
    {
        $this->created_by_admin_id = $created_by_admin_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeImmutable
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeImmutable $reservation_date): static
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }
}