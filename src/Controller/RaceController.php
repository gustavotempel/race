<?php

namespace App\Controller;

use App\Entity\Distance;
use App\Entity\Race;
use App\Entity\Racer;
use App\Service\CsvImporter;
use App\Service\RaceClassifier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


#[AsController]
final class RaceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): Race
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $csvImporter = new CsvImporter;

        $csvData = $csvImporter($uploadedFile);

        $classifier = new RaceClassifier;

        list($longCatRacers, $mediumCatRacers) = $classifier->classifyByDistance($csvData);

        $longCatRacers = $classifier->sortOverallPlace($longCatRacers);

        $longCatRacers = $classifier->sortAgeCategoryPlace($longCatRacers);

        $longDistanceAvg = $classifier->averageFinishTime($longCatRacers);

        $mediumDistanceAvg = $classifier->averageFinishTime($mediumCatRacers);

        $race = new Race(
            title: $request->get('title'),
            date: DateTime::createFromFormat('d-m-Y', $request->get('date'))->setTime(0, 0),
            longDistanceAvg: $longDistanceAvg,
            mediumDistanceAvg: $mediumDistanceAvg,
        );
        $race->setResultsFile($request->files->get('file'));
        $this->entityManager->persist($race);

        foreach($longCatRacers as $racer) {
            $newRacer = new Racer(
                fullName: $racer['fullName'],
                distance: Distance::long,
                time: $racer['time'],
                ageCategory: $racer['ageCategory'],
                race: $race,
            );
            $newRacer->setOverallPlace($racer['overallPlace']);
            $newRacer->setAgeCategoryPlace($racer['ageCategoryPlace']);
            $this->entityManager->persist($newRacer);
        }

        foreach($mediumCatRacers as $racer) {
            $newRacer = new Racer(
                fullName: $racer['fullName'],
                distance: Distance::medium,
                time: $racer['time'],
                ageCategory: $racer['ageCategory'],
                race: $race,
            );
            $this->entityManager->persist($newRacer);
        }

        $this->entityManager->flush();

        return $race;
    }
}