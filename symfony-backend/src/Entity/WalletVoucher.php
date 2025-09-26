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
    private ?int $wallet_id = null;

    #[ORM\Column]
    private ?int $voucher_id = null;

    #[ORM\Column]
    private ?int $remaining_uses = null;

    
    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'walletVoucher')]
    #[ORM\JoinColumn(name: 'wallet_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Wallet $wallet = null;

    
    #[ORM\ManyToOne(targetEntity: Voucher::class, inversedBy: 'walletVoucher')]
    #[ORM\JoinColumn(name: 'voucher_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Voucher $voucher = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWalletId(): ?int
    {
        return $this->wallet_id;
    }

    public function setWalletId(int $wallet_id): static
    {
        $this->wallet_id = $wallet_id;

        return $this;
    }

    public function getVoucherId(): ?int
    {
        return $this->voucher_id;
    }

    public function setVoucherId(int $voucher_id): static
    {
        $this->voucher_id = $voucher_id;

        return $this;
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

}

