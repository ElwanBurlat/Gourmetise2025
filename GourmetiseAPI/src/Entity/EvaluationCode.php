<?php

namespace App\Entity;

use App\Repository\EvaluationCodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationCodeRepository::class)]
class EvaluationCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $code = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(name: 'bakery_siret', referencedColumnName: 'siret', nullable: false)]
    private ?Bakery $bakery = null;

    #[ORM\Column]
    private ?bool $used = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getBakery(): ?Bakery
    {
        return $this->bakery;
    }

    public function setBakery(Bakery $bakery): static
    {
        $this->bakery = $bakery;
        return $this;
    }

    public function isUsed(): ?bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): static
    {
        $this->used = $used;
        return $this;
    }
}
