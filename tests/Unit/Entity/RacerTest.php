<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Distance;
use App\Entity\Race;
use App\Entity\Racer;
use DateTime;
use PHPUnit\Framework\TestCase;

use App\Controller\RaceController;

class RacerTest extends TestCase
{
    public function testCreateARace(): void
    {
        $mockRace = new Race(
            title: 'Mock Race Title',
            date: DateTime::createFromFormat('d-m-Y', '31-12-2022'),
            longDistanceAvg: '12:34:56.789',
            mediumDistanceAvg: '01:23:45.678',
        );
        $testRacer = new Racer(
            fullName: 'Usain Bolt',
            distance: Distance::long,
            time: '12:34:56.789',
            ageCategory: 'M30-40',
            race: $mockRace,
        );

        self::assertSame('Usain Bolt', $testRacer->getFullName());
        self::assertSame(Distance::long, $testRacer->getDistance());
        self::assertSame('12:34:56.789', $testRacer->getTime());
        self::assertSame('M30-40', $testRacer->getAgeCategory());
    }
}