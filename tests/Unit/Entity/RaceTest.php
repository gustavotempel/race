<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Race;
use DateTime;
use PHPUnit\Framework\TestCase;

class RaceTest extends TestCase
{
    public function testCreateARace(): void
    {
        $race = new Race(
            title: "Test Race Title",
            date: DateTime::createFromFormat('d-m-Y', '31-12-2022')->setTime(0, 0),
            longDistanceAvg: "12:34:56.789",
            mediumDistanceAvg: "01:23:45.678",
        );
        self::assertSame('Test Race Title', $race->getTitle());
        self::assertSame('31-12-2022', $race->getDate()->format('d-m-Y'));
        self::assertSame('12:34:56.789', $race->getLongDistanceAvg());
        self::assertSame('01:23:45.678', $race->getMediumDistanceAvg());
    }
}