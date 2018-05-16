<?php

declare(strict_types=1);

namespace App\Test\Mongo\Document;

use App\Mongo\Document\FlightItinerary;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Mongo\Document\FlightInfo
 * @covers \App\Mongo\Document\FlightItinerary
 */
class FlightItineraryTest extends TestCase
{
    public function testFlightItinerary()
    {
        //given
        $externalId = '12';
        $flightNumber = '222';
        $from = 'ADB';
        $to = 'DST';
        $dateStart = '2019-09-12 11:00:00';
        $dateEnd = '2019-09-12 12:00:00';

        //when
        $sut = new FlightItinerary(
            $externalId,
            $flightNumber,
            $from,
            $to,
            $dateStart,
            $dateEnd
        );

        $result = $sut->toArray();

        //then
        $expectedResult = [
            'externalId' => $externalId,
            'flightNumber' => $flightNumber,
            'from' => $from,
            'to' => $to,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ];

        $this->assertEquals($expectedResult, $result);
    }
}