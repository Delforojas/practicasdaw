<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'wallets')]
    #[ORM\JoinColumn(
        name: 'user_id',
        referencedColumnName: 'id',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?User $user = null;

    /**
     * @var Collection<int, WalletVoucher>
     */
    #[ORM\OneToMany(
        mappedBy: 'wallet',
        targetEntity: WalletVoucher::class
    )]
    private Collection $walletVouchers;

    public function __construct()
    {
        $this->walletVouchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, WalletVoucher>
     */
    public function getWalletVouchers(): Collection
    {
        return $this->walletVouchers;
    }

    public function addWalletVoucher(WalletVoucher $walletVoucher): static
    {
        if (!$this->walletVouchers->contains($walletVoucher)) {
            $this->walletVouchers->add($walletVoucher);
            $walletVoucher->setWallet($this);
        }

        return $this;
    }

    public function removeWalletVoucher(WalletVoucher $walletVoucher): static
    {
        if ($this->walletVouchers->removeElement($walletVoucher)) {
            if ($walletVoucher->getWallet() === $this) {
                $walletVoucher->setWallet(null);
            }
        }

        return $this;
    }
}