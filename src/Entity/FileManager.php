<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\FileManager\PhotoFileManager;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get"={
 *              "normalization_context"={"groups"={"file_manager:output", "file_manager:item:get"}},
 *          },
 *               "put", "delete"},
 *     normalizationContext={"groups"={"file_manager:output"}, "swagger_definition_name"="output"},
 *     denormalizationContext={"groups"={"file_manager:input"}, "swagger_definition_name"="input"},
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "json", "html", "jsonhal"}
 *     },
 *      shortName="files",
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isPublished"})
 * @ApiFilter(SearchFilter::class, properties={"originalFilename": "partial", "filename": "partial", "description": "partial" })
 * @ORM\Entity(repositoryClass="App\Repository\FileManagerRepository")
 */
class FileManager
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"files:output", "files:input", "article_reference:get", "article_reference:put",})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"files:output", "files:input", "article_reference:get", "article_reference:put",})
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"files:output", "files:input", "article_reference:get"})
     */
    private $isPublished = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"files:output", "files:input", "article_reference:get", "article_reference:put",})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $directory;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", inversedBy="images")
     * @ORM\JoinTable(name="images_articles")
     * @Groups({"files:output", "files:input", "article_reference:get", "article_reference:put",})
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Animal", inversedBy="images")
     * @ORM\JoinTable(name="images_animals")
     */
    private $animals;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->animals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getImagePath(): string
    {
        return PhotoFileManager::IMAGE . '/' . $this->getFilename();
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
        }

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
        }

        return $this;
    }
}
