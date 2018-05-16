<?php

declare(strict_types=1);

namespace App\Test\Mongo\Document\Factory;

use App\CsvImport\CsvLineKeyMap;
use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\Factory\FlightItineraryFactory;
use PHPUnit\Framework\TestCase;

class FlightItineraryFactoryTest extends TestCase
{
    /** @var FlightItineraryFactory */
    private $sut;

    public function setUp()
    {
        $this->sut = new FlightItineraryFactory();
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
        $flight = [
            [
                CsvLineKeyMap::KEY_EXTERNAL_ID => $externalId,
                CsvLineKeyMap::KEY_FLIGHT_NUMBER => $flightNumber,
                CsvLineKeyMap::KEY_FROM => $from,
                CsvLineKeyMap::KEY_TO => $to,
                CsvLineKeyMap::KEY_DATE_START => $dateStart,
                CsvLineKeyMap::KEY_DATE_END => $dateEnd,
            ]
        ];

        //when
        $result = $this->sut->createFromArray($flight);

        //then
        $this->assertInstanceOf(DocumentCollection::class, $result);

        $expectedResult =  [
            'externalId' => $externalId,
            'flightNumber' => $flightNumber,
            'from' => $from,
            'to' => $to,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd
        ];

        $this->assertCount(1, $result->getDocuments());
        $this->assertEquals($expectedResult, $result->getDocuments()[0]->toArray());
    }
}