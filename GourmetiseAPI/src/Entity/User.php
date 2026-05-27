<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['User:Write'])]
    #[ORM\Column(length: 30)]
    private ?string $lastName = null;

    #[Groups(['User:Write'])]
    #[ORM\Column(length: 30)]
    private ?string $firstName = null;

    #[Groups(['User:Write'])]
    #[ORM\Column(length: 50, unique : true)]
    private ?string $email = null;

    #[Groups(['User:Write'])]
    #[ORM\Column(length: 255)]
    private ?string $passwordHash = null;

    #[Groups(['User:Write'])]
    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $data_consent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $data_decline = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $password): static
    {
        $this->passwordHash = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        throw new \Exception('Not implemented');
    }

    public function getPassword(): ?string
    {
        throw new \Exception('Not implemented');
    }

    public function setPassword(string $password) {

    }

    public function eraseCredentials(): void
    {
        throw new \Exception('Not implemented');
    }

    public function getRoles(): array
    {
        throw new \Exception('Not implemented');
    }

    public function getDataConsent(): ?\DateTimeImmutable
    {
        return $this->data_consent;
    }

    public function setDataConsent(?\DateTimeImmutable $data_consent): static
    {
        $this->data_consent = $data_consent;
        return $this;
    }

    public function getDataDecline(): ?\DateTimeImmutable
    {
        return $this->data_decline;
    }

    public function setDataDecline(?\DateTimeImmutable $data_decline): static
    {
        $this->data_decline = $data_decline;
        return $this;
    }
}
