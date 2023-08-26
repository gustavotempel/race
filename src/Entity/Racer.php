<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Patch(),
        ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    )
]
#[ApiFilter(SearchFilter::class,
    properties: ['fullName' => 'exact', 'distance' => 'exact', 'ageCategory' => 'exact']),
]
#[ApiFilter(OrderFilter::class,
    properties: ['fullName', 'distance', 'time', 'ageCategory', 'overallPlace', 'ageCategoryPlace'],
    arguments: ['orderParameterName' => 'order']),
]
class Racer
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    /** The full name of the racer */
    #[ORM\Column(type: 'text')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private string $fullName;

    /** Race distance, could be 'medium' or 'long' */
    #[ORM\Column(type: 'text', enumType: Distance::class)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private Distance $distance;

    /** Total time of the race */
    #[ORM\Column(type: 'text')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private string $time;

    /** Age and gender category */
    #[ORM\Column(type: 'text')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private string $ageCategory;

    /** Overall place */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read'])]
    private ?int $overallPlace;

    /** Age category place */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read'])]
    private ?int $ageCategoryPlace;

    /** The race of the racer */
    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'racers')]
    private Race $race;

    /**
     * @param string $fullName
     * @param Distance $distance
     * @param string $time
     * @param string $ageCategory
     * @param Race $race
     */
    public function __construct(string $fullName, Distance $distance, string $time, string $ageCategory, Race $race)
    {
        $this->fullName = $fullName;
        $this->distance = $distance;
        $this->time = $time;
        $this->ageCategory = $ageCategory;
        $this->race = $race;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getDistance(): Distance
    {
        return $this->distance;
    }

    public function setDistance(Distance $distance): void
    {
        $this->distance = $distance;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    public function getAgeCategory(): string
    {
        return $this->ageCategory;
    }

    public function setAgeCategory(string $ageCategory): void
    {
        $this->ageCategory = $ageCategory;
    }

    public function getOverallPlace(): string
    {
        return $this->overallPlace;
    }

    public function getAgeCategoryPlace(): string
    {
        return $this->ageCategoryPlace;
    }

    public function setOverallPlace(int $overallPlace): void
    {
        $this->overallPlace = $overallPlace;
    }

    public function setAgeCategoryPlace(int $ageCategoryPlace): void
    {
        $this->ageCategoryPlace = $ageCategoryPlace;
    }

    public function setRace(Race $race): void
    {
        $this->race = $race;
    }


}