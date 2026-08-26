<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, WalletVoucher>
     */
    #[ORM\OneToMany(
        mappedBy: 'voucher',
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
            $walletVoucher->setVoucher($this);
        }

        return $this;
    }

    public function removeWalletVoucher(WalletVoucher $walletVoucher): static
    {
        if ($this->walletVouchers->removeElement($walletVoucher)) {
            if ($walletVoucher->getVoucher() === $this) {
                $walletVoucher->setVoucher(null);
            }
        }

        return $this;
    }
}