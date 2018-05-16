<?php

declare(strict_types=1);

namespace App\Test\Controller;

use App\Controller\FlightController;
use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Collection\FlightItineraryCollection;
use App\Mongo\Collection\FlightPriceCollection;
use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\FlightInfo;
use App\Mongo\Document\FlightPrice;
use App\Mongo\Exception\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class FlightControllerTest extends TestCase
{
    /** @var FlightController */
    private $sut;
    /** @var MockObject | FlightInfoCollection */
    private $flightInfoCollectionMock;
    /** @var MockObject | FlightItineraryCollection */
    private $flightItineraryCollectionMock;
    /** @var MockObject | FlightPriceCollection */
    private $flightPriceCollectionMock;

    public function setUp()
    {
        $this->flightInfoCollectionMock = $this->createMock(FlightInfoCollection::class);
        $this->flightItineraryCollectionMock = $this->createMock(FlightItineraryCollection::class);
        $this->flightPriceCollectionMock = $this->createMock(FlightPriceCollection::class);

        $this->sut = new FlightController(
            $this->flightInfoCollectionMock,
            $this->flightItineraryCollectionMock,
            $this->flightPriceCollectionMock
        );
    }

    public function testGet_ShouldReturnJsonResponse200_IfFlightWasFound()
    {
        //given
        $externalId = 12;
        $flightInfoArray = ['flightInfo'];
        $flightPriceArray = ['flightPrice'];
        $flightItineraryArray = ['flightItinerary'];

        $flightInfoMock = $this->createMock(FlightInfo::class);
        $flightInfoMock->method('toArray')->willReturn($flightInfoArray);

        $flightPriceMock = $this->createMock(FlightPrice::class);
        $flightPriceMock->method('toArray')->willReturn($flightPriceArray);

        $flightItineraryMock = $this->createMock(DocumentCollection::class);
        $flightItineraryMock->method('toArray')->willReturn($flightItineraryArray);

        //expects
        $query = ['externalId'=>$externalId];

        $this->flightInfoCollectionMock->method('findOneBy')
            ->with($query)
            ->willReturn($flightInfoMock);

        $this->flightPriceCollectionMock->method('findOneBy')
            ->with($query)
            ->willReturn($flightPriceMock);

        $this->flightItineraryCollectionMock->method('findBy')
            ->with($query)
            ->willReturn($flightItineraryMock);

        //when
        $expectedResult = [
            'flightInfo' => $flightInfoArray,
            'flightPrice' => $flightPriceArray,
            'flightItinerary' => $flightItineraryArray
        ];

        $result = $this->sut->get($externalId);

        //then
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedResult, json_decode($result->getContent(),true));
    }

    public function testGet_ShouldReturnJsonResponseWith404_WhenNotFoundExceptionWasThrows()
    {
        //given
        $externalId = 12;

        //expects
        $query = ['externalId'=>$externalId];

        $this->flightInfoCollectionMock->method('findOneBy')
            ->with($query)
            ->willThrowException(new NotFoundException());


        //when
        $result = $this->sut->get($externalId);

        //then
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $result->getStatusCode());
    }
}