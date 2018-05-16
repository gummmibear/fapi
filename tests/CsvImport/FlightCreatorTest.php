<?php

declare(strict_types=1);

namespace App\Tests\CsvImport;

use App\CsvImport\CsvImport;
use App\CsvImport\FlightCreator;
use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Collection\FlightItineraryCollection;
use App\Mongo\Collection\FlightPriceCollection;

use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\Factory\FlightInfoFactory;
use App\Mongo\Document\Factory\FlightItineraryFactory;
use App\Mongo\Document\Factory\FlightPriceFactory;

use App\Mongo\Document\FlightInfo;
use App\Mongo\Document\FlightPrice;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class FlightCreatorTest extends TestCase
{
    /** @var FlightCreator */
    private $sut;

    /** @var MockObject | FlightInfoCollection */
    private $flightInfoCollectionMock;
    /** @var MockObject | FlightItineraryCollection */
    private $flightItineraryCollectionMock;
    /** @var MockObject | FlightPriceCollection */
    private $flightPriceCollectionMock;
    /** @var MockObject | FlightInfoFactory */
    private $flightInfoFactoryMock;
    /** @var MockObject | FlightItineraryFactory */
    private $flightItineraryFactoryMock;
    /** @var MockObject | FlightPriceFactory */
    private $flightPriceFactoryMock;

    public function setUp()
    {
        $this->flightInfoCollectionMock = $this->createMock(FlightInfoCollection::class);
        $this->flightItineraryCollectionMock = $this->createMock(FlightItineraryCollection::class);
        $this->flightPriceCollectionMock = $this->createMock(FlightPriceCollection::class);
        $this->flightInfoFactoryMock = $this->createMock(FlightInfoFactory::class);
        $this->flightItineraryFactoryMock = $this->createMock(FlightItineraryFactory::class);
        $this->flightPriceFactoryMock = $this->createMock(FlightPriceFactory::class);

        $this->sut = new FlightCreator(
            $this->flightInfoFactoryMock,
            $this->flightInfoCollectionMock,
            $this->flightItineraryFactoryMock,
            $this->flightItineraryCollectionMock,
            $this->flightPriceFactoryMock,
            $this->flightPriceCollectionMock
        );
    }


    public function testSaveBatch_ShouldCallFactoryAndCreateCollections()
    {
        //given
        $externalIdFirst = 666;

        $flightsArrayFirst = [['flight1'], ['flight2'], ['flight3']];
        $priceArrayFirst = [['price']];

        $flights = [
            $externalIdFirst => [
                CsvImport::FLIGHTS_KEY => $flightsArrayFirst,
                CsvImport::PRICE_KEY => $priceArrayFirst
            ],
        ];

        $flightInfoMock = $this->createMock(FlightInfo::class);
        $flightItineraryDocumentCollectionMock = $this->createMock(DocumentCollection::class);
        $flightPriceMock = $this->createMock(FlightPrice::class);


        //expects
        $this->flightInfoFactoryMock->method('createFromAirports')
            ->with(['flight1'], ['flight3'])
            ->willReturn($flightInfoMock);

        $this->flightItineraryFactoryMock->method('createFromArray')
            ->with($flightsArrayFirst)
            ->willReturn($flightItineraryDocumentCollectionMock);

        $this->flightPriceFactoryMock->method('createFromArray')
            ->with($priceArrayFirst)
            ->willReturn($flightPriceMock);

        $this->flightInfoCollectionMock
            ->expects($this->once())
            ->method('insertMany')
            ->with($this->isInstanceOf(DocumentCollection::class));

        $this->flightItineraryCollectionMock
            ->expects($this->once())
            ->method('insertMany')
            ->with($this->isInstanceOf(DocumentCollection::class));


        //wehn
        $this->sut->saveBatch($flights);
    }
}