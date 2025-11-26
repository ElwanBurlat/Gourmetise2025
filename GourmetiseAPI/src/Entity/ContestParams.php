<?php

namespace App\Entity;

use App\Enum\Status;
use App\Repository\ContestParamsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContestParamsRepository::class)]
class ContestParams
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups(['ContestParams:Read', 'ContestParams:Write', 'ContestParams:Update'])]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Groups(['ContestParams:Read', 'ContestParams:Write','ContestParams:Update'])]
    private ?string $description = null;

    #[Groups(['ContestParams:Update','ContestParams:Write'])]
    #[ORM\Column(enumType: Status::class, nullable: false, options: ["default" => "not_opened"])]
    private ?Status $status = Status::NOT_OPENED;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    #[Groups(['ContestParams:Read'])]
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }

}
