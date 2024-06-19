<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @final */
#[UniqueEntity(fields: ['email', 'username'], message: 'There is already an account with this nickname or email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // final public const ROLE_ADMIN = 'ROLE_ADMIN';
    
    final public const ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 25)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\Email(message: 'Incorrect email {{ value }}')]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    /** @var string[] */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isVerified = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isExpired = false;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Compound> $compound */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Compound::class, orphanRemoval: true)]
    private Collection $compound;

    public function __construct()
    {
        $this->compound = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // #[\Override]
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // return (string) $this->username;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // #[\Override]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // #[\Override]
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if ([] === $roles) {// $roles[] = 'ROLE_USER';
            $roles[] = self::ROLE_USER;
        }

        return array_unique($roles);
    }

    /** @param string[] $roles */
    public function setRoles(array $roles): void // self
    {
        $this->roles = $roles;

        // return $this;
    }

    // #[\Override]
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @return array{int|null, string|null, string|null} */
    public function __serialize(): array
    {
        return [$this->id, $this->username, $this->password];
    }

    /** @param array{int|null, string, string} $data */
    public function __unserialize(array $data): void
    {
        [$this->id, $this->username, $this->password] = $data;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->isVerified = $is_verified;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getIsExpired(): ?bool
    {
        return $this->isExpired;
    }

    public function setIsExpired(bool $is_expired): self
    {
        $this->isExpired = $is_expired;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Compound>
     */
    public function getCompound(): Collection
    {
        return $this->compound;
    }

    public function addCompound(Compound $compound): static
    {
        if (!$this->compound->contains($compound)) {
            $this->compound->add($compound);
            $compound->setUser($this);
        }

        return $this;
    }

    public function removeCompound(Compound $compound): static
    {
        if ($this->compound->removeElement($compound)) {
            // set the owning side to null (unless already changed)
            if ($compound->getUser() === $this) {
                $compound->setUser(null);
            }
        }

        return $this;
    }
}
