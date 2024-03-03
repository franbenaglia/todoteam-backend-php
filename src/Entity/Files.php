<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilesRepository::class)]
class Files
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::GUID, nullable: true)]
    private ?string $id = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $data;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_type = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $id_task = null;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->file_type;
    }

    public function setFileType(?string $file_type): static
    {
        $this->file_type = $file_type;

        return $this;
    }

    public function getIdTask(): ?string
    {
        return $this->id_task;
    }

    public function setIdTask(?string $id_task): static
    {
        $this->id_task = $id_task;

        return $this;
    }

}
