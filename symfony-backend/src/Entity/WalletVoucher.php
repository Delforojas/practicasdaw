<?php

namespace App\Entity;

use App\Repository\WalletVoucherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletVoucherRepository::class)]
class WalletVoucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $remaining_uses = null;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'walletVouchers')]
    #[ORM\JoinColumn(
        name: 'wallet_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?Wallet $wallet = null;

    #[ORM\ManyToOne(targetEntity: Voucher::class, inversedBy: 'walletVouchers')]
    #[ORM\JoinColumn(
        name: 'voucher_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?Voucher $voucher = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemainingUses(): ?int
    {
        return $this->remaining_uses;
    }

    public function setRemainingUses(int $remaining_uses): static
    {
        $this->remaining_uses = $remaining_uses;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): static
    {
        $this->voucher = $voucher;

        return $this;
    }
}