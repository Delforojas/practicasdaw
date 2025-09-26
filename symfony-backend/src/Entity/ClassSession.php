<?php

namespace App\Entity;

use App\Repository\ClassSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassSessionRepository::class)]
class ClassSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $room_id = null;

    #[ORM\Column]
    private ?int $teacher_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date_time = null;

    #[ORM\Column]
    private ?int $duration_minutes = null;

    #[ORM\Column]
    private ?int $max_capacity = null;

    #[ORM\Column(length: 100)]
    private ?string $class_type = null;


    #[ORM\ManyToOne(targetEntity: Room::class)]
    #[ORM\JoinColumn(name: "room_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?Room $room = null;

    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "teacher_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private ?User $teacher = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): static
    {
        $this->room_id = $room_id;
        return $this;
    }

    public function getTeacherId(): ?int
    {
        return $this->teacher_id;
    }

    public function setTeacherId(int $teacher_id): static
    {
        $this->teacher_id = $teacher_id;
        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateTime(): ?\DateTimeImmutable
    {
        return $this->date_time;
    }

    public function setDateTime(\DateTimeImmutable $date_time): static
    {
        $this->date_time = $date_time;
        return $this;
    }

    public function getDurationMinutes(): ?int
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(int $duration_minutes): static
    {
        $this->duration_minutes = $duration_minutes;
        return $this;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->max_capacity;
    }

    public function setMaxCapacity(int $max_capacity): static
    {
        $this->max_capacity = $max_capacity;
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
}