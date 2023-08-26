<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\RaceController;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            controller: RaceController::class,
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                        'description' => '',
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'description' => 'The title of the race',
                                        'example' => 'Giro di Italia'
                                    ],
                                    'date' => [
                                        'type' => 'string',
                                        'format' => 'date',
                                        'description' => 'The date of the race',
                                        'example' => '31-12-2023'
                                    ],

                                ]
                            ]
                        ]
                    ])
                )
            ),
            deserialize: false,
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    ),
]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'exact'])]
#[ApiFilter(OrderFilter::class,
    properties: ['title', 'date', 'mediumDistanceAvg', 'longDistanceAvg'],
    arguments: ['orderParameterName' => 'order']),
]
class Race
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    /** The title of the race */
    #[ORM\Column(type: 'text')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private string $title;

    /** The date of the race */
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'datetime')]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd-m-Y'])]
    #[Assert\NotNull]
    private DateTimeInterface $date;

    /** Average finish time for long distance */
    #[ORM\Column(type: 'text')]
    #[Groups(['read'])]
    private string $longDistanceAvg;

    /** Average finish time for medium distance */
    #[ORM\Column(type: 'text')]
    #[Groups(['read'])]
    private string $mediumDistanceAvg;

    #[Vich\UploadableField(mapping: "csv_file", fileNameProperty: "filePath")]
    public ?File $resultsFile = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

    /** @var Racer[] Racer results for this race */
    #[ORM\OneToMany(mappedBy: "race", targetEntity: "Racer", cascade: ["persist", "remove"])]
    private iterable $racers;

    /**
     * @param string $title
     * @param DateTimeInterface $date
     * @param string $longDistanceAvg
     * @param string $mediumDistanceAvg
     */
    public function __construct(string $title, DateTimeInterface $date, string $longDistanceAvg, string $mediumDistanceAvg)
    {
        $this->title = $title;
        $this->date = $date;
        $this->longDistanceAvg = $longDistanceAvg;
        $this->mediumDistanceAvg = $mediumDistanceAvg;
        $this->racers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getMediumDistanceAvg(): string
    {
        return $this->mediumDistanceAvg;
    }

    public function getLongDistanceAvg(): string
    {
        return $this->longDistanceAvg;
    }

    public function getRacers(): iterable
    {
        return $this->racers;
    }

    public function setResultsFile(File $resultsFile): void
    {
        $this->resultsFile = $resultsFile;
    }

    public function setMediumDistanceAvg(string $mediumDistanceAvg): void
    {
        $this->mediumDistanceAvg = $mediumDistanceAvg;
    }

    public function setLongDistanceAvg(string $longDistanceAvg): void
    {
        $this->longDistanceAvg = $longDistanceAvg;
    }

    public function setRacers(iterable $racers): void
    {
        $this->racers = $racers;
    }
}