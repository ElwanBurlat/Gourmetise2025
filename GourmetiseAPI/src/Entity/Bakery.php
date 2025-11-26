<?php

namespace App\Entity;

use App\Repository\BakeryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BakeryRepository::class)]
class Bakery
{
    #[ORM\Id]
    #[ORM\Column(length: 15)]
    #[Groups(['Bakery:Write'])]
    private ?string $siret = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 30)]
    private ?string $companyName = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length:20)]
    private ?string $phone = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 50)]
    private ?string $adress = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column]
    private ?int $postalcode = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 50)]
    private ?string $country = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 55)]
    private ?string $nameContact = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 20)]
    private ?string $phoneContact = null;

    #[Groups(['Bakery:Write'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $bakeryUser = null;

   
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalcode(): ?int
    {
        return $this->postalcode;
    }

    public function setPostalcode(int $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getNameContact(): ?string
    {
        return $this->nameContact;
    }

    public function setNameContact(string $nameContact): static
    {
        $this->nameContact = $nameContact;

        return $this;
    }

    public function getPhoneContact(): ?string
    {
        return $this->phoneContact;
    }

    public function setPhoneContact(string $phoneContact): static
    {
        $this->phoneContact = $phoneContact;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBakeryUser(): ?User
    {
        return $this->bakeryUser;
    }

    public function setBakeryUser(User $bakeryUser): static
    {
        $this->bakeryUser = $bakeryUser;

        return $this;
    }
}
