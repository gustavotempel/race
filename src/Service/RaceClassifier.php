<?php

namespace App\Service;

use DateTime;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class RaceClassifier
{
    /**
     * @param float $seconds
     * @return string
     */
    private function secToTime(float $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        $u = ($seconds - floor($seconds)) * 1000000;
        $time = new \DateTime;
        return $time->setTime($h, $m, $s, $u)->format("H:i:s.v");
    }

    /**
     * @param string $time
     * @return float
     */
    private function timeToSec(string $time): float
    {
        if (DateTime::createFromFormat('H:i:s', $time)) {
            list($h, $m, $s) = explode(':', $time);
            return $h * 3600 + $m * 60 + $s;
        }
        else {
            throw new UnsupportedMediaTypeHttpException('Incorrect file format');
        }
    }

    /**
     * @param array $racers
     * @return string
     */
    public function averageFinishTime(array $racers): string
    {
        $sum = 0.0;
        foreach ($racers as $racer) {
            $sum += $this->timeToSec($racer['time']);

        }
        $avg = $sum/count($racers);
        return $this->secToTime($avg);
    }

    /**
     * @param $racers
     * @return array[]
     */
    public function classifyByDistance($racers): array
    {
        $longCatRacers = array();
        $mediumCatRacers = array();
        foreach ($racers as $racer) {
            if ($racer['distance'] == 'long') $longCatRacers[] = $racer;
            elseif ($racer['distance'] == 'medium') $mediumCatRacers[] = $racer;
        }
        return array($longCatRacers, $mediumCatRacers);
    }

    /**
     * @param array $racers
     * @return array
     */
    public function sortOverallPlace(array $racers): array
    {
        array_multisort(array_column($racers, 'time'), SORT_ASC, $racers);
        foreach ($racers as $index => $racer) {
            $racers[$index]['overallPlace'] = $index + 1;
        }
        return $racers;
    }

    /**
     * @param array $racers
     * @return array
     */
    public function sortAgeCategoryPlace(array $racers): array
    {
        array_multisort(array_column($racers, 'ageCategory'), SORT_ASC,
            array_column($racers, 'time'), SORT_ASC, $racers);
        $ageCategory = '';
        $ageCategoryPlace = 0;
        foreach ($racers as $index => $racer) {
            if ($racers[$index]['ageCategory'] == $ageCategory) {
                $ageCategoryPlace += 1;
                $racers[$index]['ageCategoryPlace'] = $ageCategoryPlace;
            }
            else {
                $ageCategoryPlace = 1;
                $racers[$index]['ageCategoryPlace'] = $ageCategoryPlace;
            }
            $ageCategory = $racers[$index]['ageCategory'];
        }
        return $racers;
    }
}