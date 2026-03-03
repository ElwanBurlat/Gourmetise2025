<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $code = null;

    #[ORM\Column]
    private ?float $welcome = null;

    #[ORM\Column]
    private ?float $shopPresentation = null;

    #[ORM\Column]
    private ?float $productQuality = null;

    #[ORM\Column(length: 15)]
    private ?string $bakery_id = null;

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

    public function getWelcome(): ?float
    {
        return $this->welcome;
    }

    public function setWelcome(float $welcome): static
    {
        $this->welcome = $welcome;

        return $this;
    }

    public function getShopPresentation(): ?float
    {
        return $this->shopPresentation;
    }

    public function setShopPresentation(float $shopPresentation): static
    {
        $this->shopPresentation = $shopPresentation;

        return $this;
    }

    public function getProductQuality(): ?float
    {
        return $this->productQuality;
    }

    public function setProductQuality(float $productQuality): static
    {
        $this->productQuality = $productQuality;

        return $this;
    }

    public function getBakeryId(): ?string
    {
        return $this->bakery_id;
    }

    public function setBakeryId(string $bakery_id): static
    {
        $this->bakery_id = $bakery_id;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
