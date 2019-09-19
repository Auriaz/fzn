<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
     * @Groups({"files:output", "files:input", "article_reference:input",})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"files:output", "files:input", "article_reference:output", "article_reference:input",})
     */
    private $originalFilename;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"files:output", "files:input", "article_reference:output"})
     */
    private $isPublished = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"files:output", "files:input", "article_reference:output", "article_reference:input",})
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

    public function getFilePath(): string
    {
        return UploaderHelper::ARTICLE_REFERENCE . '/' . $this->getFilename();
    }

    // public function getArticleReference(): ?ArticleReference
    // {
    //     return $this->articleReference;
    // }

    // public function setArticleReference(?ArticleReference $articleReference): self
    // {
    //     $this->articleReference = $articleReference;

    //     // set (or unset) the owning side of the relation if necessary
    //     $newFileManager = $articleReference === null ? null : $this;
    //     if ($newFileManager !== $articleReference->getFileManager()) {
    //         $articleReference->setFileManager($newFileManager);
    //     }

    //     return $this;
    // }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }
}
