<?php

namespace App\Test\Mongo\Document\Factory;

use App\CsvImport\CsvLineKeyMap;
use App\Mongo\Document\Factory\FlightInfoFactory;
use App\Mongo\Document\FlightInfo;
use PHPUnit\Framework\TestCase;

class FlightInfoFactoryTest extends TestCase
{
    /** @var FlightInfoFactory */
    private $sut;

    public function setUp()
    {
        $this->sut = new FlightInfoFactory();
    }

    public function testCreateFromAirports_ShouldCreateFlightInfo()
    {
        //given
        $externalId = 'externalId';
        $flightNumber = 'flightNumber';
        $dateStart = 'dateStart';
        $dateEnd = 'dateEnd';
        $from = 'POZ';
        $to = 'WAW';

        $firstAirport = [
            CsvLineKeyMap::KEY_EXTERNAL_ID => $externalId,
            CsvLineKeyMap::KEY_FLIGHT_NUMBER => $flightNumber,
            CsvLineKeyMap::KEY_DATE_START => $dateStart,
            CsvLineKeyMap::KEY_FROM => $from,
        ];

        $lastAirport = [
            CsvLineKeyMap::KEY_TO => $to,
            CsvLineKeyMap::KEY_DATE_END => $dateEnd
        ];

        //when
        $result = $this->sut->createFromAirports($firstAirport, $lastAirport);

        //then
        $this->assertInstanceOf(FlightInfo::class, $result);

        $expectedResult =  [
            'externalId' => $externalId,
            'flightNumber' => $flightNumber,
            'from' => $from,
            'to' => $to,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ];

        $this->assertEquals($expectedResult, $result->toArray());
    }
}