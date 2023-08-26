<?php

namespace App\Tests\Unit\Entity;

use App\Service\RaceClassifier;
use PHPUnit\Framework\TestCase;

class RaceClassifierTest extends TestCase
{
    public function testClassifyByDistance(): void
    {
        $testClassifier = new RaceClassifier;

        $mockData = [
            array('fullName'=>'Test Name 1', 'distance'=>'long'),
            array('fullName'=>'Test Name 2', 'distance'=>'medium'),
        ];

        list($longResults, $mediumResults) = $testClassifier->classifyByDistance($mockData);

        self::assertSame([array('fullName'=>'Test Name 1', 'distance'=>'long')], $longResults);
        self::assertSame([array('fullName'=>'Test Name 2', 'distance'=>'medium')], $mediumResults);
    }

    public function testRetrieveAvgTime(): void
    {
        $testClassifier = new RaceClassifier;

        $mockData = [
            array('fullName'=>'Test Name 1', 'time'=>'01:00:00'),
            array('fullName'=>'Test Name 2', 'time'=>'02:00:01'),
        ];

        $avgTime = $testClassifier->averageFinishTime($mockData);

        self::assertSame('01:30:00.500', $avgTime);
    }

    public function testSortOverallPlace(): void
    {
        $testClassifier = new RaceClassifier;

        $mockData = [
            array('fullName'=>'Test Name 1', 'time'=>'03:00:00'),
            array('fullName'=>'Test Name 2', 'time'=>'01:00:00'),
            array('fullName'=>'Test Name 3', 'time'=>'02:00:00'),
        ];

        $sortedRacers = $testClassifier->sortOverallPlace($mockData);

        self::assertSame('01:00:00', $sortedRacers[0]['time']);
        self::assertSame(1, $sortedRacers[0]['overallPlace']);
        self::assertSame('02:00:00', $sortedRacers[1]['time']);
        self::assertSame(2, $sortedRacers[1]['overallPlace']);
        self::assertSame('03:00:00', $sortedRacers[2]['time']);
        self::assertSame(3, $sortedRacers[2]['overallPlace']);
    }

    public function testSortAgeCategoryPlace(): void
    {
        $testClassifier = new RaceClassifier;

        $mockData = [
            array('fullName' => 'Test Name 1', 'time' => '03:00:00', 'ageCategory' => 'M18-30'),
            array('fullName' => 'Test Name 2', 'time' => '01:00:00', 'ageCategory' => 'M18-30'),
            array('fullName' => 'Test Name 3', 'time' => '02:00:00', 'ageCategory' => 'F18-30'),
            array('fullName' => 'Test Name 4', 'time' => '01:30:00', 'ageCategory' => 'F18-30'),
        ];

        $sortedRacers = $testClassifier->sortOverallPlace($mockData);
        $sortedRacers = $testClassifier->sortAgeCategoryPlace($mockData);

        self::assertSame('01:30:00', $sortedRacers[0]['time']);
        self::assertSame(1, $sortedRacers[0]['ageCategoryPlace']);
        self::assertSame('02:00:00', $sortedRacers[1]['time']);
        self::assertSame(2, $sortedRacers[1]['ageCategoryPlace']);
        self::assertSame('01:00:00', $sortedRacers[2]['time']);
        self::assertSame(1, $sortedRacers[2]['ageCategoryPlace']);
        self::assertSame('03:00:00', $sortedRacers[3]['time']);
        self::assertSame(2, $sortedRacers[3]['ageCategoryPlace']);
    }
}