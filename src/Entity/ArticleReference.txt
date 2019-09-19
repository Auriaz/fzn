<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get", "put", "delete"},
 *     normalizationContext={"groups"={"article_reference:output"}, "swagger_definition_name"="output"},
 *     denormalizationContext={"groups"={"article_reference:input"}, "swagger_definition_name"="input"},
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "json", "html", "jsonhal"}
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "fileManager": "exact",
 *      "fileManager.originalFilename": "partial"
 * })
 * @ORM\Entity(repositoryClass="App\Repository\ArticleReferenceRepository")
 */
class ArticleReference
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"main", "article_reference:output", "article_reference:input"})
     */
    private $id;

    // /**
    //  * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="articleReferences")
    //  * @ORM\JoinColumn(nullable=false)
    //  * @Groups({"article_reference:output", "article_reference:input"})
    //  */
    // private $article;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"article_reference:output", "article_reference:input"})
     */
    private $position = 0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FileManager", inversedBy="articleReference", cascade={"persist", "remove"})
     * @Groups({"article_reference:output", "article_reference:input", "file_manager:item:get"})
     */
    private $fileManager;

    // public function __construct(Article $article)
    // {
    //     $this->article = $article;
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getArticle(): ?Article
    // {
    //     return $this->article;
    // }

    public function getFilePath(): string
    {
        return UploaderHelper::ARTICLE_REFERENCE . '/' . $this->getFilename();
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getFileManager(): ?FileManager
    {
        return $this->fileManager;
    }

    public function setFileManager(?FileManager $fileManager): self
    {
        $this->fileManager = $fileManager;

        return $this;
    }
}
