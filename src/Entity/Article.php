<?php

namespace App\Entity;



use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

use ApiPlatform\Metadata\Put;
use App\Controller\ArticleCountController;
use App\Controller\ArticlePublishController;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[
    ApiResource(


        normalizationContext: ['groups' => ['read:collection'], 'openapi_definition_name' => 'Collection'],

        denormalizationContext: ['groups' => ['write:Post']],
        paginationItemsPerPage: 2,
        paginationMaximumItemsPerPage: 7,
        paginationClientItemsPerPage: true,





        operations: [
            new Get(
                name: 'GET', uriTemplate: '/articles/count', controller: ArticleCountController::class,
                read:false,
                openapiContext: [
                    'pagination_enable' => false,
                    'filters' => [],
                    'summary' => 'Récupère le nombre total d\'articles',
                    'parameters' => [
                        [
                            'in' => 'query',
                            'name' => 'online',
                            'schema' => [
                                'type' => 'integer',
                                'maximum' => 1,
                                'minimum' => 0
                            ],
                            'description' => 'Filtre les articles en ligne'
                        ]
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Ok',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'typpe' => 'integer',
                                        'example' => 3
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]

            ),



            new Put(),
            new Patch(),

            new Delete(),

            new Get(
                normalizationContext: [
                    'groups' => ['read:collection', 'read:item', 'read:Post'],
                    'openapi_definition_name' => 'Detail',


                ]

            ),

            new GetCollection(),

            new Post(
                normalizationContext: [
                    'groups' => ['read:collection', 'read:item', 'read:Post']
                ]
            ),

            new Post(
                name: 'publish',
                uriTemplate: '/articles/{id}/publish',
                controller: ArticlePublishController::class,
                write:false,
                openapiContext: [

                    'summary' => 'Permet de publier un article',
                    'requestBody' =>  [
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object'
                                ]
                            ]
                        ]
                    ]
                ]
            ),
        ]



    )

]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'content' => 'partial'])]



class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Groups(['read:collection', 'write:Post']),
        Length(min: 5)
    ]

    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'write:Post'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item', 'write:Post'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:item'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles', cascade: ['persist'])]
    #[
        Groups(['read:item', 'write:Post']),
        Valid()
    ]
    private ?Category $category = null;

    #[ORM\Column]
    #[
        Groups(['read:collection']),
        ApiProperty(openapiContext: ['type' =>'boolean', 'description' => 'En ligne ou pas ?'])

    ]
    private ?bool $online = false;




    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }


}
