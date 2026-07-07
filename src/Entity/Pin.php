<?php

namespace App\Entity;

use App\Repository\PinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;



#[ORM\Entity(repositoryClass: PinRepository::class)]
#[Vich\Uploadable]
class Pin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut oas être vide")]
    #[Assert\Length(min: 3, max: 255,   minMessage: "Le titre est trop court")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

 #[ORM\Column(length: 255, nullable: true)]
 private ?string $imageName = null;

#[Vich\UploadableField(mapping: 'pin_images', fileNameProperty: 'imageName', size: 'imageSize')]
#[Assert\Image(
    maxSize: '5M',
    mimeTypes: ['image/jpeg', 'image/png', 'image/webo'],
    mimeTypesMessage: ' Merci de déposer une image valide (jpeg, png, webp)'
)]

private ?File $imageFile = null;

public function getImageName(): ?string
{
    return $this->imageName;
}

public function setImageName(?string $imageName): static
{
    $this->imageName = $imageName;
    return $this;
}

 #[ORM\Column(nullable: true)]
 private ?int $imageSize = null;

public function getImageSize(): ?int
{
    return $this->imageSize;
}

public function setImageSize(?int $imageSize): static
{
    $this->imageSize = $imageSize;
    return $this;
}

public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;
    if(null != $imageFile){
        $this->updatedAt = new \DateTimeImmutable();
    }
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}



    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

       #[ORM\ManyToOne(inversedBy: 'pins')]
       #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    } 
}
